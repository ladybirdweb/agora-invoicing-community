<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\License\LicensePermissionsController;
use App\License\Services\ProductBundleStampingService;
use App\Model\Common\Setting;
use App\Model\Order\Order;
use App\Model\Product\ProductUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as Attach;
use Illuminate\Validation\Rule;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Exception\NoKeyLoadedException;
use phpseclib3\Net\SFTP;
use phpseclib3\Net\SSH2;
use RuntimeException;
use Symfony\Component\HttpFoundation\IpUtils;

class DeployController extends Controller
{
    /** Resolved once per request; every SSH/SFTP connection goes to this IP, never back to the hostname. */
    private string $ip = '';

    private mixed $credential = null;

    public function __construct(protected ProductBundleStampingService $stampingService)
    {
        $this->middleware('auth');
    }

    public function getVersions(int $orderId): JsonResponse
    {
        try {
            $order = Order::with('subscription')->findOrFail($orderId);

            if (! authorizeOwnership($order->client)) {
                return errorResponse(__('message.unauthorized_action'), 403);
            }

            if ($reason = $this->deployBlockedReason($order)) {
                return errorResponse($reason, 403);
            }

            $versions = $this->deployableUploads($order)
                ->select('id', 'version', 'title', 'file')
                ->latest()
                ->get();

            return successResponse('', $versions->toArray());
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return errorResponse(__('message.record_not_found'));
        } catch (\Exception $e) {
            \Logger::exception($e);

            return errorResponse(__('message.sorry_something_wrong'), 500);
        }
    }

    public function deployStep(Request $request): JsonResponse
    {
        $installing = fn (): bool => $request->step === 'install';

        $request->validate([
            'step' => 'required|in:verify,install,upload,extract',
            'order_id' => 'required|integer',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'auth_method' => 'required|in:private_key,password',
            'private_key' => 'nullable|string|required_if:auth_method,private_key',
            'password' => 'nullable|string|required_if:auth_method,password',
            'deploy_mode' => 'required|in:fresh_install,extract_only',
            // verify may run before the path is known (the wizard's connection-only check on "Next").
            'deploy_path' => ['nullable', 'string', 'max:1000', Rule::requiredIf(fn (): bool => $request->deploy_mode === 'extract_only' && $request->step !== 'verify')],
            'install_domain' => ['nullable', 'string', 'max:255', Rule::requiredIf($installing)],
            'install_email' => ['nullable', 'email', 'max:255', Rule::requiredIf($installing)],
            'web_server' => ['nullable', 'in:1,2', Rule::requiredIf($installing)],
            'ssl_type' => ['nullable', 'in:A,B,C', Rule::requiredIf($installing)],
            'ssl_cert_path' => ['nullable', 'string', 'max:500', Rule::requiredIf(fn (): bool => $installing() && $request->ssl_type === 'C')],
            'ssl_key_path' => ['nullable', 'string', 'max:500', Rule::requiredIf(fn (): bool => $installing() && $request->ssl_type === 'C')],
            'web_user' => ['nullable', 'string', 'max:64', 'regex:/^[a-z_][a-z0-9_.-]*$/i'],
            'sudo_password' => 'nullable|string',
            'version_id' => 'nullable|integer',
        ], [
            'host.required' => __('message.deploy_host_required'),
            'port.required' => __('message.deploy_port_invalid'),
            'port.integer' => __('message.deploy_port_invalid'),
            'port.min' => __('message.deploy_port_invalid'),
            'port.max' => __('message.deploy_port_invalid'),
            'username.required' => __('message.deploy_username_required'),
            'password.required_if' => __('message.deploy_password_required'),
            'private_key.required_if' => __('message.deploy_private_key_required'),
            'deploy_path.required' => __('message.deploy_path_required'),
            'install_domain.required' => __('message.deploy_domain_required'),
            'install_email.required' => __('message.deploy_email_required'),
            'install_email.email' => __('message.deploy_email_invalid'),
            'ssl_cert_path.required' => __('message.deploy_cert_path_required'),
            'ssl_key_path.required' => __('message.deploy_key_path_required'),
        ]);

        try {
            $order = Order::with('subscription')->find((int) $request->order_id);

            if (! $order || ! authorizeOwnership($order->client)) {
                return errorResponse(__('message.unauthorized_action'), 403);
            }

            if ($reason = $this->deployBlockedReason($order)) {
                return errorResponse($reason, 403);
            }

            $ip = $this->publicIp($request->host);
            if ($ip === null) {
                return errorResponse(__('message.deploy_host_not_allowed'), 422);
            }
            $this->ip = $ip;

            try {
                $this->credential = $request->auth_method === 'private_key'
                    ? PublicKeyLoader::load(trim($request->private_key))
                    : $request->password;
            } catch (NoKeyLoadedException $e) {
                return errorResponse(__('message.deploy_invalid_private_key', ['error' => $e->getMessage()]), 422);
            }

            $deployPath = $request->deploy_mode === 'fresh_install'
                ? '/var/www/faveo'
                : rtrim((string) $request->deploy_path, '/');

            return match ($request->step) { // @phpstan-ignore match.unhandled
                'verify' => $this->stepVerify($request, $deployPath),
                'install' => $this->stepInstall($request, $order),
                'upload' => $this->stepUpload($request, $order),
                'extract' => $this->stepExtract($request, $order, $deployPath),
            };
        } catch (\Exception $e) {
            \Logger::exception($e);

            return errorResponse(__('message.sorry_something_wrong'));
        }
    }

    private function stepVerify(Request $request, string $deployPath): JsonResponse
    {
        $ssh = new SSH2($this->ip, (int) $request->port);

        if ($error = $this->login($ssh, $request)) {
            return $error;
        }

        // No path yet = connection-only check; the full verify on Deploy also creates the path.
        if ($request->deploy_mode === 'extract_only' && $deployPath !== '') {
            $ssh->exec('mkdir -p '.escapeshellarg($deployPath));

            if ($ssh->getExitStatus() !== 0) {
                return errorResponse(__('message.deploy_path_create_failed', ['path' => $deployPath]));
            }
        }

        return successResponse(__('message.deploy_ssh_verified'));
    }

    private function stepInstall(Request $request, Order $order): JsonResponse
    {
        $installScript = Setting::value('help_support_url');
        if (empty($installScript)) {
            return errorResponse(__('message.deploy_install_script_not_configured'), 422);
        }

        $ssh = new SSH2($this->ip, (int) $request->port);
        $ssh->setTimeout(1800);

        if ($error = $this->login($ssh, $request)) {
            return $error;
        }

        // One answer per line, in the order the install script prompts for them.
        // The license line comes from the order itself, never from the request.
        // ponytail: the trailing "y" on the license line is kept from the original — confirm against the script.
        $answers = [
            $request->install_domain,
            $request->install_email,
            $order->serial_key.$order->number.'y',
            (string) $request->web_server,
            $request->ssl_type,
        ];
        if ($request->ssl_type === 'C') {
            array_push($answers, $request->ssl_cert_path, $request->ssl_key_path);
        }

        $output = (string) $ssh->exec(
            'wget -q -O /tmp/faveo_install.sh '.escapeshellarg($installScript).' && '.
            "printf '%s\\n' ".implode(' ', array_map(escapeshellarg(...), $answers)).' | bash /tmp/faveo_install.sh 2>&1; '.
            'rc=$?; rm -f /tmp/faveo_install.sh; exit $rc'
        );

        if ($ssh->getExitStatus() !== 0) {
            return errorResponse(__('message.deploy_install_script_errors'));
        }

        $credentials = trim((string) $ssh->exec(
            'cat ~/credentials.txt 2>/dev/null || cat /root/credentials.txt 2>/dev/null || echo ""'
        ));

        return successResponse(__('message.deploy_install_completed'), [
            'credentials' => $credentials,
            'setup_url' => 'http://'.$request->install_domain.'/',
            'output' => $output,
        ]);
    }

    private function stepUpload(Request $request, Order $order): JsonResponse
    {
        $upload = $this->deployableUploads($order)
            ->when($request->filled('version_id'), fn ($q) => $q->where('id', $request->version_id))
            ->latest()
            ->first();

        if (! $upload) {
            return errorResponse(__('message.renew_subscription_download'));
        }

        $product = $upload->product;

        if (! $product) {
            return errorResponse(__('message.deploy_file_not_found'));
        }

        $resolvedFile = $upload->resolvedFile();
        $filePath = 'products/'.$resolvedFile;

        if (empty($resolvedFile) || ! Attach::exists($filePath)) {
            return errorResponse(__('message.deploy_file_not_found'));
        }

        try {
            $tmpFile = $this->stampingService->stampToLocalFile($filePath, $product, $upload->version, $order);
        } catch (RuntimeException) {
            return errorResponse(__('message.deploy_upload_failed'));
        }

        try {
            $sftp = new SFTP($this->ip, (int) $request->port);

            if ($error = $this->login($sftp, $request)) {
                return $error;
            }

            if (! $sftp->put($this->remoteZipPath($order), $tmpFile, SFTP::SOURCE_LOCAL_FILE)) {
                return errorResponse(__('message.deploy_upload_failed'));
            }
        } finally {
            @unlink($tmpFile);
        }

        return successResponse(__('message.deploy_file_uploaded'));
    }

    private function stepExtract(Request $request, Order $order, string $deployPath): JsonResponse
    {
        $sudoPassword = $request->filled('sudo_password') ? $request->sudo_password : null;

        $withSudo = fn (string $cmd) => $sudoPassword
            ? 'echo '.escapeshellarg($sudoPassword).' | sudo -S '.$cmd
            : $cmd;

        $ssh = new SSH2($this->ip, (int) $request->port);
        $ssh->setTimeout(300);

        if ($error = $this->login($ssh, $request)) {
            return $error;
        }

        $webUser = $request->filled('web_user') ? $request->web_user : $this->detectWebUser($ssh);
        $zip = escapeshellarg($this->remoteZipPath($order));
        $path = escapeshellarg($deployPath);

        // "user:" = that user plus its login group.
        $output = (string) $ssh->exec(
            $withSudo("unzip -o {$zip} -d {$path}").' 2>&1 && '.
            $withSudo('chown -R '.escapeshellarg($webUser.':')." {$path}").' 2>&1; '.
            "rc=\$?; rm -f {$zip}; exit \$rc"
        );

        if ($ssh->getExitStatus() !== 0) {
            return str_contains($output, 'Permission denied')
                ? errorResponse(__('message.deploy_permission_denied', ['path' => $deployPath]))
                : errorResponse(__('message.deploy_failed_generic'));
        }

        $siteUrl = null;
        if ($request->deploy_mode === 'extract_only') {
            $domainCmd =
                'domain=""; '.
                'f=$(grep -rlF '.escapeshellarg('DocumentRoot '.$deployPath).' /etc/apache2/sites-enabled/ 2>/dev/null | head -1); '.
                '[ -n "$f" ] && domain=$(grep -iE "^[[:space:]]*ServerName[[:space:]]+" "$f" 2>/dev/null | head -1 | awk \'{print $2}\'); '.
                'if [ -z "$domain" ]; then '.
                ' f=$(grep -rlF '.escapeshellarg('root '.$deployPath).' /etc/nginx/sites-enabled/ /etc/nginx/conf.d/ 2>/dev/null | head -1); '.
                ' [ -n "$f" ] && domain=$(grep -iE "^[[:space:]]*server_name[[:space:]]+" "$f" 2>/dev/null | grep -v "#" | head -1 | awk \'{print $2}\' | tr -d ";"); '.
                'fi; '.
                'echo "$domain"';

            $detected = trim((string) $ssh->exec($domainCmd));
            if ($detected && ! str_contains($detected, ' ') && str_contains($detected, '.')) {
                $siteUrl = 'http://'.$detected;
            }
        }

        return successResponse(__('message.deploy_extract_completed'), [
            'output' => $output,
            'site_url' => $siteUrl,
        ]);
    }

    /** Same rules as BaseProductController::userDownload — deploy must never hand out what download wouldn't. */
    private function deployBlockedReason(Order $order): ?string
    {
        if (! Setting::where('id', 1)->value('deployment_enabled')) {
            return __('message.no_permission_for_action');
        }

        $permissions = LicensePermissionsController::getPermissionsForProduct((int) $order->getRawOriginal('product'));
        if (($permissions['downloadPermission'] ?? 0) != 1) {
            return __('message.no_permission_for_action');
        }

        $subscription = $order->subscription;
        if (! $subscription) {
            return __('message.no_order_exists_invoice');
        }

        if (! ($permissions['allowDownloadTillExpiry'] ?? false)
            && $subscription->update_ends_at
            && now()->gt($subscription->update_ends_at)) {
            return __('message.renew_subscription_download');
        }

        return null;
    }

    /** @return Builder<ProductUpload> */
    private function deployableUploads(Order $order): Builder
    {
        $updateEndsAt = $order->subscription?->update_ends_at;

        return ProductUpload::where('product_id', $order->getRawOriginal('product'))
            ->where('is_private', 0)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->when($updateEndsAt, fn ($q) => $q->where('created_at', '<', $updateEndsAt));
    }

    /**
     * Null for hosts that resolve to private/loopback/reserved ranges — customers deploy to their own public servers, never ours.
     * WHITELIST_DEPLOY_IP (config custom.whitelist_deploy_ip) opens specific private IPs/ranges for dev/QA VMs.
     */
    private function publicIp(string $host): ?string
    {
        $ip = filter_var($host, FILTER_VALIDATE_IP) ? $host : gethostbyname($host);

        if (filter_var($ip, FILTER_VALIDATE_IP) && IpUtils::checkIp($ip, config('custom.whitelist_deploy_ip', []))) {
            return $ip;
        }

        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) ?: null;
    }

    private function login(SSH2 $ssh, Request $request): ?JsonResponse
    {
        if ($ssh->login($request->username, $this->credential)) {
            return null;
        }

        $errors = implode(' | ', $ssh->getErrors() ?: [__('message.deploy_auth_failed_fallback')]);

        return errorResponse(__('message.deploy_ssh_auth_failed', ['errors' => $errors]));
    }

    /** The PHP/web worker user. The deploy dir's owner is no use: verify just created it as the SSH user. */
    private function detectWebUser(SSH2 $ssh): string
    {
        $firstUserRunning = fn (string $pattern): string => trim((string) $ssh->exec(
            "ps -eo user=,comm= | awk '\$1 != \"root\" && \$2 ~ /{$pattern}/ {print \$1; exit}'"
        ));

        $user = $firstUserRunning('php-fpm') ?: $firstUserRunning('^(apache2|httpd|nginx)$');

        return preg_match('/^[a-z_][a-z0-9_.-]*$/i', $user) ? $user : 'www-data';
    }

    /** Derived on the server for both upload and extract — extract never trusts a client-sent path. */
    private function remoteZipPath(Order $order): string
    {
        return '/tmp/faveo_deploy_'.$order->id.'.zip';
    }
}
