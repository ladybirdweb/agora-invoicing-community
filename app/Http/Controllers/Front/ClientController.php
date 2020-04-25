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
use DateTime;
use DateTimeZone;
use Exception;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;

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
            $invoices = Invoice::where('user_id', \Auth::user()->id)
                    ->select('number', 'created_at', 'grand_total', 'id', 'status');

            return \DataTables::of($invoices->get())
                            ->addColumn('number', function ($model) {
                                return $model->number;
                            })
                            ->addColumn('date', function ($model) {
                                $date = $model->created_at;

                                return $date;
                            })
                            ->addColumn('total', function ($model) {
                                return  currency_format($model->grand_total, $code = \Auth::user()->currency);
                            })
                            ->addColumn('Action', function ($model) {
                                $status = $model->status;
                                $payment = '';
                                if ($status == 'Pending' && $model->grand_total > 0) {
                                    $payment = '  <a href='.url('paynow/'.$model->id).
                                    " class='btn btn-primary btn-xs'><i class='fa fa-credit-card'></i>&nbsp;Pay Now</a>";
                                }

                                return '<p><a href='.url('my-invoice/'.$model->id).
                                " class='btn btn-primary btn-xs'><i class='fa fa-eye'></i>&nbsp;View</a>".$payment.'</p>';
                            })
                            ->rawColumns(['number', 'created_at', 'total', 'Action'])
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
                                } elseif (!$orderEndDate) {
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
            $orders = Order::where('client', \Auth::user()->id);

            return \DataTables::of($orders->get())
                            ->addColumn('id', function ($model) {
                                return $model->id;
                            })
                            ->addColumn('product_name', function ($model) {
                                return $model->product()->first()->name;
                            })
                            ->addColumn('expiry', function ($model) {
                                $tz = \Auth::user()->timezone()->first()->name;
                                $end = $this->getExpiryDate($model);

                                return $end;
                            })

                            ->addColumn('Action', function ($model) {
                                $sub = $model->subscription()->first();
                                $order = Order::where('id', $model->id)->select('product')->first();
                                $productid = $order->product;
                                $order_cont = new \App\Http\Controllers\Order\OrderController();
                                $status = $order_cont->checkInvoiceStatusByOrderId($model->id);
                                $url = '';
                                if ($status == 'success') {
                                    if ($sub) {
                                        $url = $this->renewPopup($sub->id, $productid);
                                    }
                                }

                                $listUrl = $this->getPopup($model, $productid);

                                return '<a href='.url('my-order/'.$model->id)." 
                                class='btn  btn-primary btn-xs' style='margin-right:5px;'>
                                <i class='fa fa-eye' title='Details of order'></i>&nbsp;View $listUrl $url </a>";
                            })
                            ->rawColumns(['id', 'created_at', 'ends_at', 'product', 'Action'])
                            ->make(true);
        } catch (Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);
            echo $ex->getMessage();
        }
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

            return view(
                'themes.default1.front.clients.profile',
                compact('user', 'timezones', 'state', 'states', 'bussinesses','is2faEnabled','dateSinceEnabled')
            );
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getOrder($id)
    {
        try {
            $order = $this->order->findOrFail($id);
            $invoice = $order->invoice()->first();
            $items = $order->invoice()->first()->invoiceItem()->get();
            $subscription = '';
            $plan = '';
            if ($order->subscription) {
                $subscription = $order->subscription;

                $plan = $subscription->plan()->first();
            }
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            if ($licenseStatus == 1) {
                $cont = new \App\Http\Controllers\License\LicenseController();
                $installationDetails = $cont->searchInstallationPath($order->serial_key, $order->product);
            }
            $product = $order->product()->first();
            $price = $product->price()->first();
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            $allowDomainStatus = StatusSetting::pluck('domain_check')->first();
            $user = \Auth::user();

            return view(
                'themes.default1.front.clients.show-order',
                compact('invoice', 'order', 'user', 'plan', 'product', 'subscription', 'licenseStatus', 'installationDetails', 'allowDomainStatus')
            );
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect('/')->with('fails', $ex->getMessage());
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
                                $total = currency_format($model->amount, $code = $currency);

                                return $total;
                            })
                            ->addColumn('payment_method', function ($model) {
                                return $model->payment_method;
                            })
                             ->addColumn('payment_status', function ($model) {
                                 return $model->payment_status;
                             })
                            ->addColumn('created_at', function ($model) {
                                $date1 = new DateTime($model->created_at);
                                $tz = \Auth::user()->timezone()->first()->name;
                                $date1->setTimezone(new DateTimeZone($tz));
                                $date = $date1->format('M j, Y, g:i a');

                                return $date;
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
            $relation = $order->invoiceRelation()->pluck('invoice_id')->toArray();
            if (count($relation) > 0) {
                $invoices = $relation;
            } else {
                $invoices = $order->invoice()->pluck('id')->toArray();
            }
            $payments = $this->payment->whereIn('invoice_id', $invoices)
                    ->select('id', 'invoice_id', 'user_id', 'payment_method', 'payment_status', 'created_at', 'amount');
            //dd(\Input::all());
            return \DataTables::of($payments->get())
                            ->addColumn('number', function ($model) {
                                return $model->invoice()->first()->number;
                            })
                              ->addColumn('total', function ($model) {
                                  return $model->amount;
                              })
                               ->addColumn('created_at', function ($model) {
                                   $date1 = new DateTime($model->created_at);
                                   $tz = \Auth::user()->timezone()->first()->name;
                                   $date1->setTimezone(new DateTimeZone($tz));
                                   $date = $date1->format('M j, Y, g:i a');

                                   return $date;
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
