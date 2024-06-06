<?php

namespace App\Jobs;

use App\ExportDetail;
use App\Exports\OrderExport;
use App\Http\Controllers\Order\OrderSearchController;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $selectedColumns;
    protected $searchParams;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($selectedColumns, $searchParams, $email)
    {
        $this->selectedColumns = $selectedColumns;
        $this->searchParams = $searchParams;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Similar logic to export users but for orders
        $this->selectedColumns = array_filter($this->selectedColumns, function ($column) {
            return ! in_array($column, ['checkbox', 'action']);
        });

        $request = new Request();
        $request->merge($this->searchParams);

        $orderSearch = new OrderSearchController();
        $orders = $orderSearch->advanceOrderSearch($request);
        //dd($orders->first());
        // foreach ($this->searchParams as $key => $value) {
            //     if ($value !== null && $value !== '') {
            //         switch ($key) {
            //             case 'from':
            //                 $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime($value)));
            //                 break;
            //             case 'till':
            //                 $orders->whereDate('created_at', '<=', date('Y-m-d', strtotime($value)));
            //                 break;
            //             case 'product_id':
            //                 $orders->where('product', $value);
            //                 break;
            //             case 'order_no':
            //                 $orders->where('number', $value);
            //                 break;
            //            case 'renewal':
            //             if ($value === 'expiring_subscription' || $value == 'expired_subscription') {
            //                 $fromDate = Carbon::createFromFormat('m/d/Y', $this->searchParams['from'])->startOfDay();
            //                 $tillDate = Carbon::createFromFormat('m/d/Y', $this->searchParams['till'])->endOfDay();

            //                 $orders = Order::join('subscriptions', 'orders.id', '=', 'subscriptions.order_id')
            //                 ->leftJoin('users', 'orders.client', '=', 'users.id')
            //                 ->leftJoin('products', 'orders.product', '=', 'products.id')
            //                 ->leftJoin('installation_details', 'orders.id', '=', 'installation_details.order_id')
            //                 ->whereDate('subscriptions.update_ends_at', '>=', $fromDate)
            //                 ->whereDate('subscriptions.update_ends_at', '<=', $tillDate)
            //                 ->distinct()
            //                 ->select('orders.*');

            //             }
            //             break;
            //             case 'act_inst':
            //                   if ($value === 'not_installed' || $value === 'installed' || $value === 'paid_inactive_ins' || $value === 'paid_ins') {
            //                     $orders = $orders->join('subscriptions', 'orders.id', '=', 'subscriptions.order_id');
            //                     $orders = $this->allInstallations($value, $orders);
            //                 }
            //                 break;
            //            case 'domain':
            //                 if ($value) {
            //                     if (str_finish($value, '/')) {
            //                         $value = substr_replace($value, '', -1, 0);
            //                     }
            //                     $ins = InstallationDetail::where('Installation_path', $value)->pluck('order_id');
            //                     $orders = Order::whereIn('id', $ins);
            //                 }
            //                 break;

            //             case 'version':
            //             if ($value === 'paid' || $value === 'unpaid' || $value === 'Latest' || $value === 'Outdated') {
            //                 $productId = $this->searchParams['product_id'] ?? null;
            //                 $orders = Order::join('subscriptions', 'orders.id', '=', 'subscriptions.order_id');
            //                 $orders = $this->getSelectedVersionOrders($orders, $value, $productId, $this->searchParams);
            //             }
            //             break;
            //             default:
            //             $orders->where($key, $value);
            //             break;
            //         }
            //     }
        // }

        $orders->orderBy('orders.created_at', 'desc');

        $filteredOrders = $orders->get()->map(function ($order) {
            $orderData = [];
            foreach ($this->selectedColumns as $column) {
                switch ($column) {
                    case 'client':
                        $orderData['name'] = $order->client_name;
                        break;
                    case 'email':
                        $orderData['email'] = $order->email;
                        break;
                    case 'mobile':
                        $orderData['mobile'] = $order->mobile;
                        break;
                    case 'country':
                        $orderData['country'] = $order->country;
                        break;
                    case 'status':
                        $orderData['status'] = $order->installation_path ? 'Active' : 'Inactive';
                        break;
                    case 'product_name':
                        $orderData['product_name'] = $order->product_name;
                        break;
                    case 'plan_name':
                        $plan = Plan::find($order->plan_id);
                        $orderData['plan_name'] = $plan ? $plan->name : 'Unknown Plan';
                        break;
                    case 'version':
                        $orderData['version'] = $order->product_version;
                        break;
                    case 'agents':
                        $orderData['agents'] = $this->getAgents($order);
                        break;
                    case 'order_date':
                        $orderData['order_date'] = \Carbon\Carbon::parse($order->subscription_created_at)->format('Y-m-d');
                        break;
                    case 'update_ends_at':
                        $orderData['update_ends_at'] = \Carbon\Carbon::parse($order->subscription_updated_at)->format('Y-m-d');
                        break;
                    default:
                        $orderData[$column] = $order->$column;
                }
            }

            return $orderData;
        });

        $ordersData = $filteredOrders;

        $export = new OrderExport($this->selectedColumns, $ordersData);
        $id = User::where('email', $this->email)->value('id');
        $user = User::find($id);
        $timestamp = now()->format('Ymd_His');
        $fileName = 'orders_'.$id.'_'.$timestamp.'.xlsx';
        $filePath = storage_path('app/public/export/'.$fileName);
        Excel::store($export, 'public/export/'.$fileName);

        $exportDetail = ExportDetail::create([
            'user_id' => $id,
            'file_path' => storage_path('app/public/export/'.$fileName),
            'file' => $fileName,
            'name' => 'orders',
        ]);

        $settings = new \App\Model\Common\Setting();
        $setting = $settings::find(1);
        $from = $setting->email;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $downloadLink = route('download.exported.file', ['id' => $exportDetail->id]);
        $emailContent = 'Hello '.$user->first_name.' '.$user->last_name.','.
            '<br><br>Order report is successfully generated and ready for download.'.
            '<br><br>Download link: <a href="'.$downloadLink.'">'.$downloadLink.'</a>'.
            '<br><br>Please note this link will be expired in 6 hours.'.
            '<br><br>Kind regards,<br>'.$user->first_name;

        $mail->SendEmail($from, $this->email, $emailContent, 'Order report available for download');
    }

    public function allInstallations($allInstallation, $orders)
    {
        if ($allInstallation) {
            $dayUtc = new Carbon('-30 days');
            $minus30Day = $dayUtc->toDateTimeString();

            switch ($allInstallation) {
                case 'installed':
                    return $orders->whereColumn('subscriptions.created_at', '!=', 'subscriptions.updated_at');
                case 'not_installed':
                    return $orders->whereColumn('subscriptions.created_at', '=', 'subscriptions.updated_at');
                case 'paid_inactive_ins':
                    return $orders->where('subscriptions.updated_at', '<', $minus30Day);
                case 'paid_ins':
                    return $orders->whereColumn('subscriptions.created_at', '!=', 'subscriptions.updated_at')
                                  ->where('subscriptions.updated_at', '>', $minus30Day);
                default:
                    return $orders;
            }
        }
    }

    public function getSelectedVersionOrders($baseQuery, $version, $productId, $request)
    {
        if ($version) {
            if ($productId == 'paid' || $productId == 'unpaid') {
                $latestVersion = ProductUpload::orderBy('version', 'desc')->value('version');
                if ($version == 'Latest') {
                    $baseQuery->where('subscriptions.version', '=', $latestVersion);
                } elseif ($version == 'Outdated') {
                    $baseQuery->where('subscriptions.version', '<', $latestVersion);
                }
            } elseif ($version == 'Outdated') {
                $latestVersion = Subscription::where('product_id', $productId)
                                             ->orderBy('version', 'desc')
                                             ->value('version');
                $baseQuery->where('subscriptions.version', '!=', null)
                          ->where('subscriptions.version', '!=', '')
                          ->where('subscriptions.version', '<', $latestVersion);
            } else {
                $baseQuery->where('subscriptions.version', '=', $version);
            }
        }

        return $baseQuery;
    }

    public function getAgents($order)
    {
        $license = substr($order->serial_key, 12, 16);
        if ($license == '0000') {
            return 'Unlimited';
        }

        return intval($license, 10);
    }
}
