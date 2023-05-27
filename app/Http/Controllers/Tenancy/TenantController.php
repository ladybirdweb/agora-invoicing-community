<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Controllers\License\LicenseController;
use App\Model\Common\FaveoCloud;
use App\Model\Common\Setting;
use App\Model\Mailjob\CloudEmail;
use App\Model\Order\Order;
use App\ThirdPartyApp;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\Mime\Email;

class TenantController extends Controller
{
    private $cloud;

    public function __construct(Client $client, FaveoCloud $cloud)
    {
        $this->client = $client;
        $this->cloud = $cloud->first();

        $this->middleware('auth', ['except' => ['verifyThirdPartyToken']]);
    }

    public function viewTenant()
    {
        $cloud = $this->cloud;

        $response = $this->client->request(
            'GET',
            $this->cloud->cloud_central_domain.'/tenants'
        );

        $responseBody = (string) $response->getBody();
        $response = json_decode($responseBody, true);

        $de = collect($response['message'])->paginate(5);
        // $de = $data->all();

        return view('themes.default1.tenant.index', compact('cloud', 'de'));
    }

    public function getTenants(Request $request)
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->cloud->cloud_central_domain.'/tenants'
            );

            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);

            return \DataTables::of($response)

                ->addColumn('tenants', function ($model) {
                    return $model->id;
                })
                ->addColumn('domain', function ($model) {
                    return $model->domain;
                })
                ->addColumn('db_name', function ($model) {
                    return $model->database_name;
                })
                ->addColumn('db_username', function ($model) {
                    return $model->database_user_name;
                })
                ->addColumn('action', function ($model) {
                    return "<p><button data-toggle='modal' 
                 data-id=".$model->id." data-name= '' onclick=deleteTenant('".$model->id."') id='delten".$model->id."'
                 class='btn btn-sm btn-danger btn-xs delTenant'".tooltip('Delete')."<i class='fa fa-trash'
                 style='color:white;'> </i></button>&nbsp;</p>";
                })
                ->rawColumns(['tenants', 'domain', 'db_name', 'db_username', 'action'])
                ->make(true);
        } catch (ConnectException|Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }

        // $tenants =
    }

    private function postCurl($post_url, $post_info)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    /**
     * Logic for creating new tenant is handled here.
     */
    public function createTenant(Request $request)
    {
        $order = Order::wherenumber($request->orderNo)->get();
        $product = strpos($order[0]->product()->value('name'), 'ServiceDesk') ? 'ServiceDesk' : 'Helpdesk';
        //This above code is only written
        // to differentiate HD and SD when we reach the
        // market place feature this needs to be removed

        $this->validate($request,
            [
                'orderNo' => 'required',
                'domain' => 'required',
            ]);

        $settings = Setting::find(1);
        $user = \Auth::user()->email;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mailer = $mail->setMailConfig($settings);

        try {
            $faveoCloud = (string) $request->input('domain');
            $dns_record = dns_get_record($faveoCloud, DNS_CNAME);
            if (! strpos($faveoCloud, 'faveocloud.com')) {
                if (empty($dns_record) || ! in_array('faveocloud.com', array_column($dns_record, 'target'))) {
                    throw new Exception('Your Domains DNS CNAME record is not pointing to our cloud!(CNAME record is missing) Please do it to proceed');
                }
            }
            $licCode = Order::where('number', $request->input('orderNo'))->first()->serial_key;
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            if (! $keys->app_key) {//Valdidate if the app key to be sent is valid or not
                throw new Exception('Invalid App key provided. Please contact admin.');
            }
            $token = str_random(32);
            \DB::table('third_party_tokens')->insert(['user_id' => \Auth::user()->id, 'token' => $token]);
            $client = new Client([]);
            $data = ['domain' => $faveoCloud, 'app_key'=>$keys->app_key, 'token'=>$token, 'lic_code'=>$licCode, 'username'=>$user, 'userId'=>\Auth::user()->id, 'timestamp'=>time(), 'product'=>$product, 'product_id'=>$order[0]->product()->value('id')];

            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $response = $client->request(
                'POST',
                $this->cloud->cloud_central_domain.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
            );

            $response = explode('{', (string) $response->getBody());

            $response = '{'.$response[1];

            $result = json_decode($response);
            if ($result->status == 'fails') {
                $this->prepareMessages($faveoCloud, $user);

                return ['status' => 'false', 'message' => $result->message];
            } elseif ($result->status == 'validationFailure') {
                $this->prepareMessages($faveoCloud, $user);

                return ['status' => 'validationFailure', 'message' => $result->message];
            } else {
                if (! strpos($faveoCloud, 'faveocloud.com')) {
                    CloudEmail::create([
                        'result_message' => $result->message,
                        'user' => $user,
                        'result_password' => $result->password,
                        'domain' => $faveoCloud,
                    ]);
                    $client = new Client();
                    $client->request('GET', env('CLOUD_JOB_URL'), [
                        'auth' => ['clouduser', env('CLOUD_AUTH')],
                        'query' => [
                            'token' => env('CLOUD_OAUTH_TOKEN'),
                            'domain' => $faveoCloud,
                        ],
                    ]);

                    return ['status' => $result->status, 'message' => trans('message.create_in_progress').'.'];
                } else {
                    $client->request('GET', env('CLOUD_JOB_URL_NORMAL'), [
                        'auth' => ['clouduser', env('CLOUD_AUTH')],
                        'query' => [
                            'token' => env('CLOUD_OAUTH_TOKEN'),
                            'domain' => $faveoCloud,
                        ],
                    ]);
                    $userData = $result->message.'.<br> Email:'.' '.$user.'<br>'.'Password:'.' '.$result->password;
                    $this->prepareMessages($faveoCloud, $user, true);
                    $email = (new Email())
                        ->from($settings->email)
                        ->to($user)
                        ->subject('New instance created')
                        ->html($result->message.'.<br> Email:'.' '.$user.'<br>'.'Password:'.' '.$result->password);

                    $mailer->send($email);

                    $mail->email_log_success($settings->email, $user, 'New instance created', $result->message.'.<br> Email:'.' '.$user.'<br>'.'Password:'.' '.$result->password);

//                    $mail = new \App\Http\Controllers\Common\PhpMailController();
//
//                    $mail->sendEmail($settings->email, $user, $userData, 'New instance created');

                    return ['status' => $result->status, 'message' => $result->message.'.'];
                }
            }
        } catch (Exception $e) {
            //$mail->email_log_fail($settings->email, $user, 'New instance created', $result->message.'.<br> Email:'.' '.$user.'<br>'.'Password:'.' '.$result->password);

            return ['status' => 'false', 'message' => trans('message.something_bad')];
        }
    }

    public function verifyThirdPartyToken(Request $request)
    {
        try {
            $token = $request->input('token');
            $userId = $request->input('userId');
            $faveoToken = \DB::table('third_party_tokens')->where('user_id', $userId)->value('token');
            if ($faveoToken && $token == $faveoToken) {
                \DB::table('third_party_tokens')->where('user_id', $userId)->delete();
                //delete third party token here
                $response = ['status' => 'success', 'message' => 'Valid token'];
            } else {
                $response = ['status' => 'fails', 'message' => 'Invalid token'];
            }

            return $response;
        } catch (Exception $e) {
            $error = ['status' => 'fails', 'message' => $e->getMessage()];

            return $error;
        }
    }

    public function destroyTenant(Request $request)
    {
        try {
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $data = ['id' => $request->input('id'), 'app_key' => $keys->app_key, 'deleteTenant' => true, 'token' => $token, 'timestamp' => time()];
            $encodedData = http_build_query($data);
            $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
            $client = new Client([]);
            $response = $client->request(
                'DELETE',
                $this->cloud->cloud_central_domain.'/tenants', ['form_params' => $data, 'headers' => ['signature' => $hashedSignature]]
            );
            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);
            if ($response->status == 'success') {
                $this->deleteCronForTenant($request->input('id'));
                (new LicenseController())->reissueDomain($request->input('id'));
                return successResponse($response->message);
            } else {
                return errorResponse($response->message);
            }
        } catch (Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    private function deleteCronForTenant($tenantId)
    {
        $client = new Client();
        if (strpos($tenantId, 'faveocloud.com')) {
            $client->request('GET', env('CLOUD__DELETE_JOB_URL_NORMAL'), [
                'auth' => ['clouduser', env('CLOUD_AUTH')],
                'query' => [
                    'token' => env('CLOUD_OAUTH_TOKEN'),
                    'domain' => $tenantId,
                ],
            ]);
        } else {
            $client->request('GET', env('CLOUD__DELETE_JOB_URL_CUSTOM'), [
                'auth' => ['clouduser', env('CLOUD_AUTH')],
                'query' => [
                    'token' => env('CLOUD_OAUTH_TOKEN'),
                    'domain' => $tenantId,
                ],
            ]);
        }
    }

    public function saveCloudDetails(Request $request)
    {
        $this->validate($request, [
            'cloud_central_domain' => 'required',
        ]);

        try {
            $cloud = new FaveoCloud;
            $cloud->updateOrCreate(['id' => 1], ['cloud_central_domain' => $request->input('cloud_central_domain'), 'cron_server_url' => $request->input('cron_server_url'),
                'cron_server_key' => $request->input('cron_server_key'), ]);
            // $cloud->first()->fill($request->all())->save();
            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function changeDomain(Request $request)
    {
        $this->validate($request, [
            'currentDomain'=> 'required',
            'newDomain'=>'required',
        ]);
        $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
        $token = str_random(32);
        $newDomain = $request->get('newDomain');
        $data = ['currentDomain' => $request->get('currentDomain'), 'newDomain'=>$newDomain, 'app_key'=>$keys->app_key, 'token'=>$token, 'timestamp'=>time()];
        $dns_record = dns_get_record($newDomain, DNS_CNAME);
        if (! strpos($newDomain, 'faveocloud.com')) {
            if (empty($dns_record) || ! in_array('faveocloud.com', array_column($dns_record, 'target'))) {
                throw new Exception('Your Domains DNS CNAME record is not pointing to our cloud!(CNAME record is missing) Please do it to proceed');
            }
        }
        $encodedData = http_build_query($data);
        $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
        $client = new Client([]);
        $response = $client->request(
            'POST',
            $this->cloud->cloud_central_domain.'/changeDomain', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
        );
        if (! strpos($newDomain, 'faveocloud.com')) {
            $client->request('GET', env('CLOUD_JOB_URL_NORMAL'), [
                'auth' => ['clouduser', env('CLOUD_AUTH')],
                'query' => [
                    'token' => env('CLOUD_OAUTH_TOKEN'),
                    'domain' => $newDomain,
                ],
            ]);
        }

        return response(['status'=>true, 'message'=> 'Domain changed please reissue your license and use the license code in your domain']);
    }

    public function DeleteCloudInstanceForClient($orderNumber, $isDelete)
    {
        if ($isDelete) {
            $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();
            $token = str_random(32);
            $order_id = Order::where('number', $orderNumber)->where('client', \Auth::user()->id)->value('id');
            $installation_path = \DB::table('installation_details')->where('order_id', $order_id)->value('installation_path');
            $response = $this->client->request(
                'GET',
                $this->cloud->cloud_central_domain.'/tenants'
            );
            $responseBody = (string) $response->getBody();
            $response = json_decode($responseBody);
            $domainArray = $response->message;
            for ($i = 0; $i < count($domainArray); $i++) {
                if ($domainArray[$i]->domain == $installation_path) {
                    $data = ['id' => $domainArray[$i]->id, 'app_key'=>$keys->app_key, 'deleteTenant'=> true, 'token'=>$token, 'timestamp'=>time()];
                    $encodedData = http_build_query($data);
                    $hashedSignature = hash_hmac('sha256', $encodedData, $keys->app_secret);
                    $client = new Client([]);
                    $response = $client->request(
                        'DELETE',
                        $this->cloud->cloud_central_domain.'/tenants', ['form_params'=>$data, 'headers'=>['signature'=>$hashedSignature]]
                    );
                    $responseBody = (string) $response->getBody();
                    $response = json_decode($responseBody);
                    if ($response->status == 'success') {
                        $this->deleteCronForTenant($domainArray[$i]->id);
                        $this->reissueCloudLicense($order_id);
                        Order::where('number', $orderNumber)->where('client', \Auth::user()->id)->delete();

                        return redirect()->back()->with('success', $response->message);
                    } else {
                        return redirect()->back()->with('fails', $response->message);
                    }
                }
            }

            return redirect()->back()->with('fails', "Something went wrong, we couldn't delete your cloud instance.(mostly your cloud instance was already deleted)");
        }
    }

    protected function reissueCloudLicense($order_id)
    {
        $order = Order::findorFail($order_id);
        if (\Auth::user()->role != 'admin' && $order->client != \Auth::user()->id) {
            return errorResponse('Cannot remove license installations. Invalid modification of data');
        }
        $order->domain = '';
        $licenseCode = $order->serial_key;
        $order->save();
        $licenseStatus = \DB::table('status_settings')->pluck('license_status')->first();
        if ($licenseStatus == 1) {
            $licenseExpiry = $order->subscription->ends_at;
            $updatesExpiry = $order->subscription->update_ends_at;
            $supportExpiry = $order->subscription->support_ends_at;
            $cont = new \App\Http\Controllers\License\LicenseController();
            $updateLicensedDomain = $cont->updateLicensedDomain($licenseCode, $order->domain, $order->product, $licenseExpiry, $updatesExpiry, $supportExpiry, $order->number);
            //Now make Installation status as inactive
            $updateInstallStatus = $cont->updateInstalledDomain($licenseCode, $order->product);
        }

        return ['message' => 'success', 'update'=>'License installations removed'];
    }

    private function prepareMessages($domain, $user, $success = false)
    {
        if ($success) {
            $this->googleChat('Hello, It has come to my notice that this domain has been created successfully Domain name:'.$domain.' and this is their email: '.$user."\u{2705}\u{2705}\u{2705}");
        } else {
            $this->googleChat('Hello, It has come to my notice that this domain has not been created successfully Domain name:'.$domain.' and this is their email: '.$user.'&#10060;'."\u{2716}\u{2716}\u{2716}");
        }
    }

    private function googleChat($text)
    {
        $url = env('GOOGLE_CHAT');
        $message = [
            'text' => $text,
        ];
        $message_headers = [
            'Content-Type' => 'application/json; charset=UTF-8',
        ];
        $client = new Client();
        $client->post($url, [
            'headers' => $message_headers,
            'body' => json_encode($message),
        ]);
    }
}
