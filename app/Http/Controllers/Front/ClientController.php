<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Github\GithubApiController;
use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\StatusSetting;
use App\Model\Github\Github;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use App\Model\Product\Subscription;
use App\User;
use Bugsnag;
use Exception;
use GrahamCampbell\Markdown\Facades\Markdown;

class ClientController extends BaseClientController
{
    public $user;
    public $invoice;
    public $order;
    public $subscription;
    public $payment;

    public function __construct()
    {
        $this->middleware('auth');

        $user = new User();
        $this->user = $user;

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $order = new Order();
        $this->order = $order;

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $payment = new Payment();
        $this->payment = $payment;

        $product_upload = new ProductUpload();
        $this->product_upload = $product_upload;

        $product = new Product();
        $this->product = $product;

        $github_controller = new GithubApiController();
        $this->github_api = $github_controller;

        $model = new Github();
        $this->github = $model->firstOrFail();

        $this->client_id = $this->github->client_id;
        $this->client_secret = $this->github->client_secret;
    }

    public function invoices()
    {
        try {
            return view('themes.default1.front.clients.invoice');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getInvoices()
    {
        try {
            $invoices = Invoice::leftJoin('order_invoice_relations', 'invoices.id', '=', 'order_invoice_relations.invoice_id')
            ->select('invoices.id', 'invoices.user_id', 'invoices.date', 'invoices.number', 'invoices.grand_total', 'order_invoice_relations.order_id', 'invoices.is_renewed', 'invoices.status')
            ->groupBy('invoices.number')
            ->where('invoices.user_id', '=', \Auth::user()->id)
            ->orderBy('invoices.created_at', 'desc')
            ->get();

            return \DataTables::of($invoices)
                            ->addColumn('number', function ($model) {
                                if ($model->is_renewed) {
                                    return '<a href='.url('my-invoice/'.$model->id).'>'.$model->number.'</a>&nbsp;'.getStatusLabel('renewed', 'badge');
                                } else {
                                    return '<a href='.url('my-invoice/'.$model->id).'>'.$model->number.'</a>';
                                }
                            })
                           ->addColumn('orderNo', function ($model) {
                               if ($model->is_renewed) {
                                   return Order::find($model->order_id)->first()->getOrderLink($model->order_id, 'my-order');
                               } else {
                                   $allOrders = $model->order()->select('id', 'number')->get();
                                   $orderArray = '';
                                   foreach ($allOrders as $orders) {
                                       $orderArray .= $orders->getOrderLink($orders->id, 'orders');
                                   }

                                   return $orderArray;
                               }
                           })
                            ->addColumn('date', function ($model) {
                                return getDateHtml($model->created_at);
                            })
                            ->addColumn('total', function ($model) {
                                return  currencyFormat($model->grand_total, $code = \Auth::user()->currency);
                            })
                             ->addColumn('status', function ($model) {
                                 return  getStatusLabel($model->status, 'badge');
                             })
                            ->addColumn('Action', function ($model) {
                                $status = $model->status;
                                $payment = '';
                                if ($status != 'Success' && $model->grand_total > 0) {
                                    $payment = '  <a href='.url('paynow/'.$model->id).
                                    " class='btn btn-primary btn-xs'><i class='fa fa-credit-card'></i>&nbsp;Pay Now</a>";
                                }

                                return '<p><a href='.url('my-invoice/'.$model->id).
                                " class='btn btn-primary btn-xs'><i class='fa fa-eye'></i>&nbsp;View</a>".$payment.'</p>';
                            })

                            ->rawColumns(['number', 'orderNo', 'date', 'total', 'status', 'Action'])
                            // ->orderColumns('number', 'created_at', 'total')
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    /**
     * Get list of all the versions from Filesystem.
     *
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     *
     * Get list of all the versions from Filesystem.
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     *
     * @return type
     */
    public function getVersionList($productid, $clientid, $invoiceid)
    {
        try {
            $versions = ProductUpload::where('product_id', $productid)
            ->select(
                'id',
                'product_id',
                'version',
                'title',
                'description',
                'file',
                'created_at'
            )->get();
            $countVersions = count($versions);
            $countExpiry = 0;
            $invoice_id = Invoice::where('number', $invoiceid)->pluck('id')->first();
            $order = Order::where('invoice_id', '=', $invoice_id)->first();

            $order_id = $order->id;
            $updatesEndDate = Subscription::select('update_ends_at')
                 ->where('product_id', $productid)->where('order_id', $order_id)->first();
            if ($updatesEndDate) {
                foreach ($versions as $version) {
                    if ($version->created_at->toDateTimeString()
                    < $updatesEndDate->update_ends_at || $updatesEndDate->update_ends_at == '0000-00-00 00:00:00') {
                        $countExpiry = $countExpiry + 1;
                    }
                }
            }

            return \DataTables::of($versions)
                            ->addColumn('id', function ($versions) {
                                return ucfirst($versions->id);
                            })
                            ->addColumn('version', function ($versions) {
                                return ucfirst($versions->version);
                            })
                            ->addColumn('title', function ($versions) {
                                return ucfirst($versions->title);
                            })
                            ->addColumn('description', function ($versions) {
                                return ucfirst($versions->description);
                            })
                            ->addColumn('file', function ($versions) use ($countExpiry, $countVersions, $clientid, $invoiceid, $productid) {
                                $invoice_id = Invoice::where('number', $invoiceid)->pluck('id')->first();
                                $order = Order::where('invoice_id', '=', $invoice_id)->first();
                                $order_id = $order->id;
                                $downloadPermission = LicensePermissionsController::getPermissionsForProduct($productid);
                                $updateEndDate = Subscription::select('update_ends_at')
                                ->where('product_id', $productid)->where('order_id', $order_id)->first();

                                //if product has Update expiry date ie subscription is generated
                                if ($updateEndDate) {
                                    if ($downloadPermission['allowDownloadTillExpiry'] == 1) {//Perpetual download till expiry permission selected
                                        $getDownload = $this->whenDownloadTillExpiry($updateEndDate, $productid, $versions, $clientid, $invoiceid);

                                        return $getDownload;
                                    } elseif ($downloadPermission['allowDownloadTillExpiry'] == 0) {//When download retires after subscription
                                        $getDownload = $this->whenDownloadExpiresAfterExpiry($countExpiry, $countVersions, $updateEndDate, $productid, $versions, $clientid, $invoiceid);

                                        return $getDownload;
                                    }
                                }
                            })
                            ->rawColumns(['version', 'title', 'description', 'file'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    /**
     * Get list of all the versions from Github.
     *
     * @param type $productid
     * @param type $clientid
     * @param type $invoiceid
     */
    public function getGithubVersionList($productid, $clientid, $invoiceid)
    {
        try {
            $products = $this->product::where('id', $productid)
            ->select('name', 'version', 'github_owner', 'github_repository')->get();
            $owner = '';
            $repo = '';
            foreach ($products as $product) {
                $owner = $product->github_owner;
                $repo = $product->github_repository;
            }
            $url = "https://api.github.com/repos/$owner/$repo/releases";
            $countExpiry = 0;
            $link = $this->github_api->getCurl1($url);
            $link = $link['body'];
            $countVersions = 10; //because we are taking only the first 10 versions
            $link = (array_slice($link, 0, 10, true));
            $order = Order::where('invoice_id', '=', $invoiceid)->first();
            $order_id = $order->id;
            $orderEndDate = Subscription::select('update_ends_at')
                        ->where('product_id', $productid)->where('order_id', $order_id)->first();
            if ($orderEndDate) {
                foreach ($link as $lin) {
                    if (strtotime($lin['created_at']) < strtotime($orderEndDate->update_ends_at) || $orderEndDate->update_ends_at == '0000-00-00 00:00:00') {
                        $countExpiry = $countExpiry + 1;
                    }
                }
            }

            return \DataTables::of($link)
                            ->addColumn('version', function ($link) {
                                return ucfirst($link['tag_name']);
                            })
                            ->addColumn('name', function ($link) {
                                return ucfirst($link['name']);
                            })
                            ->addColumn('description', function ($link) {
                                $markdown = Markdown::convertToHtml(ucfirst($link['body']));

                                return $markdown;
                            })
                            ->addColumn('file', function ($link) use ($countExpiry, $countVersions, $invoiceid, $productid) {
                                $order = Order::where('invoice_id', '=', $invoiceid)->first();
                                $order_id = $order->id;
                                $orderEndDate = Subscription::select('update_ends_at')
                                ->where('product_id', $productid)->where('order_id', $order_id)->first();
                                if ($orderEndDate) {
                                    $actionButton = $this->getActionButton($countExpiry, $countVersions, $link, $orderEndDate, $productid);

                                    return $actionButton;
                                } elseif (! $orderEndDate) {
                                    $link = $this->github_api->getCurl1($link['zipball_url']);

                                    return '<p><a href='.$link['header']['Location']
                                    ." class='btn btn-sm btn-primary'>Download  </a>"
                                            .'&nbsp;
                                   </p>';
                                }
                            })
                            ->rawColumns(['version', 'name', 'description', 'file'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    /*
     * Show all the orders for User
     */

    public function getOrders()
    {
        try {
            $orders = $this->getClientPanelOrdersData();

            return \DataTables::of($orders)
                            ->addColumn('id', function ($model) {
                                return $model->id;
                            })
                            ->addColumn('product_name', function ($model) {
                                return $model->product_name;
                            })
                            ->addColumn('number', function ($model) {
                                return '<a href='.url('my-order/'.$model->id).'>'.$model->number.'</a>';
                            })
                            ->addColumn('version', function ($model) {
                                return getVersionAndLabel($model->version, $model->product_id, 'badge');
                            })
                            ->addColumn('expiry', function ($model) {
                                return getExpiryLabel($model->update_ends_at, 'badge');
                            })

                            ->addColumn('Action', function ($model) {
                                $order_cont = new \App\Http\Controllers\Order\OrderController();
                                $status = $order_cont->checkInvoiceStatusByOrderId($model->id);
                                $url = '';
                                if ($status == 'success') {
                                    $url = $this->renewPopup($model->sub_id, $model->product_id);
                                }

                                $listUrl = $this->getPopup($model, $model->product_id);

                                return '<a href='.url('my-order/'.$model->id)." 
                                class='btn  btn-primary btn-xs' style='margin-right:5px;'>
                                <i class='fa fa-eye' title='Details of order'></i>&nbsp;View $listUrl $url </a>";
                            })
                            ->rawColumns(['id', 'product_name', 'number', 'version', 'expiry', 'Action'])
                            ->make(true);
        } catch (Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
    }

    public function getClientPanelOrdersData()
    {
        return Order::leftJoin('products', 'products.id', '=', 'orders.product')
            ->leftJoin('subscriptions', 'orders.id', '=', 'subscriptions.order_id')
            ->leftJoin('invoices', 'orders.invoice_id', 'invoices.id')
            ->select('products.name as product_name', 'products.github_owner', 'products.github_repository', 'products.type', 'products.id as product_id', 'orders.id', 'orders.number', 'orders.client', 'subscriptions.id as sub_id', 'subscriptions.version', 'subscriptions.update_ends_at', 'products.name', 'orders.client', 'invoices.id as invoice_id', 'invoices.number as invoice_number')
            ->where('orders.client', \Auth::user()->id)
            ->get()->map(function ($element) {
                $element->update_ends_at = strtotime($element->update_ends_at) > 1 ? $element->update_ends_at : '--';

                return $element;
            });
    }

    public function profile()
    {
        try {
            $user = $this->user->where('id', \Auth::user()->id)->first();
            $is2faEnabled = $user->is_2fa_enabled;
            $dateSinceEnabled = $user->google2fa_activation_date;
            $timezonesList = \App\Model\Common\Timezone::get();
            foreach ($timezonesList as $timezone) {
                $location = $timezone->location;
                if ($location) {
                    $start = strpos($location, '(');
                    $end = strpos($location, ')', $start + 1);
                    $length = $end - $start;
                    $result = substr($location, $start + 1, $length - 1);
                    $display[] = (['id'=>$timezone->id, 'name'=> '('.$result.')'.' '.$timezone->name]);
                }
            }
            //for display
            $timezones = array_column($display, 'name', 'id');
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);
            $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
            $selectedIndustry = \App\Model\Common\Bussiness::where('name', $user->bussiness)
            ->pluck('name', 'short')->toArray();
            $selectedCompany = \DB::table('company_types')->where('name', $user->company_type)
            ->pluck('name', 'short')->toArray();
            $selectedCompanySize = \DB::table('company_sizes')->where('short', $user->company_size)
            ->pluck('name', 'short')->toArray();

            return view(
                'themes.default1.front.clients.profile',
                compact('user', 'timezones', 'state', 'states', 'bussinesses', 'is2faEnabled', 'dateSinceEnabled', 'selectedIndustry', 'selectedCompany', 'selectedCompanySize')
            );
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrder($id)
    {
        try {
            $user = \Auth::user();
            $order = $this->order->findOrFail($id);
            if ($order->client != $user->id) {
                throw new \Exception('Cannot view order. Invalid modification of data.');
            }
            $invoice = $order->invoice()->first();
            $items = $order->invoice()->first()->invoiceItem()->get();
            $subscription = $order->subscription()->first();
            $date = '--';
            $licdate = '--';
            $versionLabel = '--';
            if ($subscription) {
                $date = strtotime($subscription->update_ends_at) > 1 ? getExpiryLabel($subscription->update_ends_at, 'badge') : '--';
                $licdate = strtotime($subscription->ends_at) > 1 ? getExpiryLabel($subscription->ends_at, 'badge') : '--';
                $versionLabel = getVersionAndLabel($subscription->version, $order->product, 'badge');
            }

            $installationDetails = [];
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            if ($licenseStatus == 1) {
                $cont = new \App\Http\Controllers\License\LicenseController();
                $installationDetails = $cont->searchInstallationPath($order->serial_key, $order->product);
            }
            $product = $order->product()->first();
            $price = $product->price()->first();
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            $allowDomainStatus = StatusSetting::pluck('domain_check')->first();

            return view(
                'themes.default1.front.clients.show-order',
                compact('invoice', 'order', 'user', 'product', 'subscription', 'licenseStatus', 'installationDetails', 'allowDomainStatus', 'date', 'licdate', 'versionLabel')
            );
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPaymentByOrderId($orderid, $userid)
    {
        try {
            // dd($orderid);
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            // dd($order);
            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            if (count($relation) > 0) {
                $invoices = $relation;
            } else {
                $invoices = $order->invoice()->pluck('id')->toArray();
            }
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'amount', 'payment_method', 'payment_status', 'created_at');

            return \DataTables::of($payments->get())
                            ->addColumn('checkbox', function ($model) {
                                return "<input type='checkbox' class='payment_checkbox' 
                                    value=".$model->id.' name=select[] id=check>';
                            })
                            ->addColumn('number', function ($model) {
                                return $model->invoice()->first()->number;
                            })
                            ->addColumn('amount', function ($model) {
                                $currency = $model->invoice()->first()->currency;
                                $total = currencyFormat($model->amount, $code = $currency);

                                return $total;
                            })
                            ->addColumn('payment_method', function ($model) {
                                return $model->payment_method;
                            })
                             ->addColumn('payment_status', function ($model) {
                                 return $model->payment_status;
                             })
                            ->addColumn('created_at', function ($model) {
                                return getDateHtml($model->created_at);
                            })
                            ->rawColumns(['checkbox', 'number', 'amount',
                                'payment_method', 'payment_status', 'created_at', ])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPaymentByOrderIdClient($orderid, $userid)
    {
        try {
            $order = $this->order->where('id', $orderid)->where('client', $userid)->first();
            // dd($order);
            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            if (count($relation) > 0) {
                $invoices = $relation;
            } else {
                $invoices = $order->invoice()->pluck('id')->toArray();
            }
            // $payments = Payment::leftJoin('invoices', 'payments.invoice_id', '=', 'invoices.id')
            // ->select('payments.id', 'payments.invoice_id', 'payments.user_id', 'payments.payment_method', 'payments.payment_status', 'payments.created_at', 'payments.amount', 'invoices.id as invoice_id', 'invoices.number as invoice_number')
            // ->where('invoices.id', $invoices)
            // ->get();
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'amount', 'payment_method', 'payment_status', 'created_at');

            return \DataTables::of($payments->get())
                            ->addColumn('number', function ($payments) {
                                return '<a href='.url('my-invoice/'.$payments->invoice()->first()->id).'>'.$payments->invoice()->first()->number.'</a>';
                            })
                              ->addColumn('total', function ($payments) {
                                  return $payments->amount;
                              })
                               ->addColumn('created_at', function ($payments) {
                                   return  getDateHtml($payments->created_at);
                               })

                            ->addColumn('payment_method', 'payment_status', 'created_at')

                            ->rawColumns(['number', 'total', 'payment_method', 'payment_status', 'created_at'])
                            ->make(true);
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
