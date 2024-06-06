<?php

namespace App\Jobs;

use App\ExportDetail;
use App\Exports\TenatExport;
use App\Model\Common\FaveoCloud;
use App\Model\Order\Order;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Subscription;
use App\ThirdPartyApp;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportTenatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $selectedColumns;
    protected $searchParams;
    protected $email;
    private $cloud;

    /**
     * Create a new job instance.
     */
    public function __construct($selectedColumns, $searchParams, $email)
    {
        $this->selectedColumns = $selectedColumns;
        $this->searchParams = $searchParams;
        $this->email = $email;
        $this->cloud = FaveoCloud::first();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $client = new Client();

        // Similar logic to export users but for orders
        $this->selectedColumns = array_filter($this->selectedColumns, function ($column) {
            return ! in_array($column, ['action']);
        });

        $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->select('app_key', 'app_secret')->first();

        if (! $keys->app_key) {
            // Validate if the app key to be sent is valid or not
            throw new \Exception('Invalid App key provided. Please contact admin.');
        }

        $response = $client->request(
            'GET',
            $this->cloud->cloud_central_domain.'/tenants',
            [
                'query' => [
                    'key' => $keys->app_key,
                ],
            ]
        );

        $responseBody = (string) $response->getBody();
        $responseData = json_decode($responseBody);

        $tenats = collect($responseData->message)->reject(function ($item) {
            return $item === null;
        });
        $filteredTenants = $tenats->map(function ($tenats) {
            $tenantData = [];
            foreach ($this->selectedColumns as $column) {
                switch ($column) {
                    case 'Order':
                        $order_id = \DB::table('installation_details')->where('installation_path', $tenats->domain)->latest()->value('order_id');
                        $order_number = \DB::table('orders')->where('id', $order_id)->value('number');
                        $tenantData['Order'] = $order_number;
                        break;
                    case 'name':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');

                        if (! $order_id) {
                            $tenantData['name'] = null;
                        } else {
                            $userId = Order::where('id', $order_id)->value('client');
                            if (! $userId) {
                                $tenantData['name'] = null;
                            } else {
                                $user = User::find($userId);
                                if (! $user) {
                                    $tenantData['name'] = null;
                                } else {
                                    $tenantData['name'] = $user->first_name.' '.$user->last_name;
                                }
                            }
                        }
                        break;

                    case 'email':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');

                        if (! $order_id) {
                            $tenantData['email'] = null;
                        } else {
                            $userId = Order::where('id', $order_id)->value('client');
                            if (! $userId) {
                                $tenantData['email'] = null;
                            } else {
                                $user = User::find($userId);
                                if (! $user) {
                                    $tenantData['email'] = null;
                                } else {
                                    $tenantData['email'] = $user->email;
                                }
                            }
                        }
                        break;

                    case 'mobile':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');

                        if (! $order_id) {
                            $tenantData['mobile'] = null;
                        } else {
                            $userId = Order::where('id', $order_id)->value('client');
                            if (! $userId) {
                                $tenantData['mobile'] = null;
                            } else {
                                $user = User::find($userId);
                                if (! $user) {
                                    $tenantData['mobile'] = null;
                                } else {
                                    $tenantData['mobile'] = $user->mobile;
                                }
                            }
                        }
                        break;

                    case 'country':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');

                        if (! $order_id) {
                            $tenantData['country'] = null;
                        } else {
                            $userId = Order::where('id', $order_id)->value('client');
                            if (! $userId) {
                                $tenantData['country'] = null;
                            } else {
                                $user = User::find($userId);
                                if (! $user) {
                                    $tenantData['country'] = null;
                                } else {
                                    $tenantData['country'] = $user->country;
                                }
                            }
                        }
                        break;

                    case 'Expiry day':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');
                        $subscription_date = Subscription::where('order_id', $order_id)->value('ends_at');
                        if (empty($subscription_date)) {
                            $tenantData['Expiry day'] = null;
                        } else {
                            $tenantData['Expiry day'] = Carbon::parse($subscription_date)->format('d M Y');
                        }
                        break;

                    case 'Deletion day':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');
                        $subscription_date = Subscription::where('order_id', $order_id)->value('ends_at');
                        if (empty($subscription_date)) {
                            $tenantData['Deletion day'] = null;
                        } else {
                            $days = \DB::table('expiry_mail_days')->where('cloud_days', '!=', null)->value('cloud_days');
                            $originalDate = Carbon::parse($subscription_date)->addDays($days);
                            $formattedDate = Carbon::parse($originalDate)->format('d M Y');
                            $tenantData['Deletion day'] = $formattedDate;
                        }
                        break;

                    case 'plan':
                        $order_id = \DB::table('installation_details')
                                    ->where('installation_path', $tenats->domain)
                                    ->latest()
                                    ->value('order_id');
                        if (empty($order_id)) {
                            $tenantData['plan'] = null;
                        } else {
                            $plan_id = Subscription::where('order_id', $order_id)->latest()->value('plan_id');
                            $price = PlanPrice::where('plan_id', $plan_id)->latest()->value('add_price');
                            $message = ($price) ? 'Paid Subscription' : 'Free Trial';
                            $tenantData['plan'] = $message;
                        }
                        break;

                    case 'tenants':
                        $order_id = \DB::table('installation_details')->where('installation_path', $tenats->domain)->latest()->value('order_id');
                        $order_number = \DB::table('orders')->where('id', $order_id)->value('number');
                        $tenantData['tenats'] = $tenats->id;
                        break;

                    case 'domain':
                        $tenantData['domain'] = $tenats->domain;
                        break;

                    case 'db_name':
                        $tenantData['db_name'] = $tenats->database_name;
                        break;

                    case 'db_username':
                        $tenantData['db_username'] = $tenats->database_user_name;
                        break;
                    default:
                        $tenantData[$column] = $tenant->$column ?? null;
                        break;
                }
            }

            return $tenantData;
        });
        $tenantData = $filteredTenants;

        // Export the tenant data
        $export = new TenatExport($this->selectedColumns, $tenantData);
        $id = User::where('email', $this->email)->value('id');
        $user = User::find($id);
        $timestamp = now()->format('Ymd_His');
        $fileName = 'tenats_'.$id.'_'.$timestamp.'.xlsx';
        $filePath = storage_path('app/public/export/'.$fileName);
        Excel::store($export, 'public/export/'.$fileName);

        $exportDetail = ExportDetail::create([
            'user_id' => $id,
            'file_path' => storage_path('app/public/export/'.$fileName),
            'file' => $fileName,
            'name' => 'tenats',
        ]);

        $settings = new \App\Model\Common\Setting();
        $setting = $settings::find(1);
        $from = $setting->email;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $downloadLink = route('download.exported.file', ['id' => $exportDetail->id]);
        $emailContent = 'Hello '.$user->first_name.' '.$user->last_name.','.
            '<br><br>Tenat report is successfully generated and ready for download.'.
            '<br><br>Download link: <a href="'.$downloadLink.'">'.$downloadLink.'</a>'.
            '<br><br>Please note this link will be expired in 6 hours.'.
            '<br><br>Kind regards,<br>'.$user->first_name;

        $mail->SendEmail($from, $this->email, $emailContent, 'Tenat report available for download');
    }
}
