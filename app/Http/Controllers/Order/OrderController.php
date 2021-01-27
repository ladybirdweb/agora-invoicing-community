<?php

namespace App\Http\Controllers\Order;

use App\Http\Requests\Order\OrderRequest;
use App\Model\Common\StatusSetting;
use App\Model\Order\InstallationDetail;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Payment\Promotion;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use App\Model\Product\Subscription;
use App\User;
use Bugsnag;
use Illuminate\Http\Request;

class OrderController extends BaseOrderController
{
    // NOTE FROM AVINASH: utha le re deva
    // NOTE: don't lose hope.
    public $order;
    public $user;
    public $promotion;
    public $product;
    public $subscription;
    public $invoice;
    public $invoice_items;
    public $price;
    public $plan;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except'=>['getInstallationDetails']]);

        $order = new Order();
        $this->order = $order;

        $user = new User();
        $this->user = $user;

        $promotion = new Promotion();
        $this->promotion = $promotion;

        $product = new Product();
        $this->product = $product;

        $subscription = new Subscription();
        $this->subscription = $subscription;

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $invoice_items = new InvoiceItem();
        $this->invoice_items = $invoice_items;

        $plan = new Plan();
        $this->plan = $plan;

        $price = new Price();
        $this->price = $price;

        $product_upload = new ProductUpload();
        $this->product_upload = $product_upload;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from'          => 'nullable',
            'till'          => 'nullable|after:from',

        ]);
        if ($validator->fails()) {
            $request->from = '';
            $request->till = '';

            return redirect('orders')->with('fails', 'Start date should be before end date');
        }
        try {
            $products = $this->product->where('id', '!=', 1)->pluck('name', 'id')->toArray();

            $paidUnpaidOptions = ['paid'=>'Paid Products', 'unpaid'=>'Unpaid Products'];
            $insNotIns = ['installed'=>'Yes (Installed atleast once)', 'not_installed'=>'No (Not Installed)'];
            $activeInstallationOptions = ['paid_ins'=>'Active installation'];
            $inactiveInstallationOptions = ['paid_inactive_ins'=>'Inactive installation'];
            $renewal = ['expired_subscription'=>'Expired Subscriptions', 'active_subscription'=> 'Active Subscriptions'];
            $selectedVersion = $request->version;
            $allVersions = Subscription::where('version', '!=', '')->whereNotNull('version')
                ->orderBy('version', 'desc')->groupBy('version')
                ->select('version')->get();

            return view('themes.default1.order.index',
                compact('request', 'products', 'allVersions', 'activeInstallationOptions', 'paidUnpaidOptions', 'inactiveInstallationOptions', 'renewal', 'insNotIns', 'selectedVersion'));
        } catch (\Exception $e) {
            return redirect('orders')->with('fails', $e->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        $orderSearch = new OrderSearchController();
        $query = $orderSearch->advanceOrderSearch($request);

        return \DataTables::of($query)
            ->setTotalRecords($query->count())
            ->addColumn('checkbox', function ($model) {
                return "<input type='checkbox' class='order_checkbox' value=".$model->id.' name=select[] id=check>';
            })
            ->addColumn('client', function ($model) {
                return '<a href='.url('clients/'.$model->client_id).'>'.ucfirst($model->client_name).'<a>';
            })
            ->addColumn('product_name', function ($model) {
                return $model->product_name;
            })
            ->addColumn('version', function ($model) {
                $installedVersions = InstallationDetail::where('order_id', $model->id)->pluck('version')->toArray();
                if (count($installedVersions)) {
                    $latest = max($installedVersions);

                    return getVersionAndLabel($latest, $model->product);
                } else {
                    return '--';
                }
            })
            ->addColumn('number', function ($model) {
                $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>';
                if ($model->subscription_updated_at) {//For few older clients subscription was not generated, so no updated_at column exists
                    $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>'.installationStatusLabel($model->subscription_updated_at, $model->subscription_created_at);
                }

                return $orderLink;
            })
            ->addColumn('order_status', function ($model) {
                return ucfirst($model->order_status);
            })
            ->addColumn('order_date', function ($model) {
                return getDateHtml($model->created_at);
            })
            ->addColumn('update_ends_at', function ($model) {
                $ends_at = strtotime($model->subscription_ends_at) > 1 ? $model->subscription_ends_at : '--';

                return getExpiryLabel($ends_at);
            })
            ->addColumn('action', function ($model) {
                $status = $this->checkInvoiceStatusByOrderId($model->id);

                return $this->getUrl($model, $status, $model->subscription_id);
            })

            ->filterColumn('client', function ($query, $keyword) {
                $query->whereRaw("concat(first_name, ' ', last_name) like ?", ["%$keyword%"]);
            })
            ->filterColumn('product_name', function ($query, $keyword) {
                $query->whereRaw('products.name like ?', ["%$keyword%"]);
            })
            ->filterColumn('version', function ($query, $keyword) {
                $query->whereRaw('subscriptions.version like ?', ["%$keyword%"]);
            })
            ->filterColumn('number', function ($query, $keyword) {
                $query->whereRaw('number like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('price_override', function ($query, $keyword) {
                $query->whereRaw('price_override like ?', ["%{$keyword}%"]);
            })
            ->filterColumn('order_status', function ($query, $keyword) {
                $query->whereRaw('order_status like ?', ["%{$keyword}%"]);
            })

            ->orderColumn('order_date', 'orders.created_at $1')
            ->orderColumn('client', 'client_name $1')
            ->orderColumn('product_name', 'product_name $1')
            ->orderColumn('version', 'product_version $1')
            ->orderColumn('number', 'number $1')
            ->orderColumn('price_override', 'price_override $1')
            ->orderColumn('order_status', 'order_status $1')
            ->orderColumn('update_ends_at', 'update_ends_at $1')

            ->rawColumns(['checkbox', 'date', 'client', 'version', 'number', 'order_status', 'order_date', 'update_ends_at', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Response
     */
    public function create()
    {
        try {
            $clients = $this->user->pluck('first_name', 'id')->toArray();
            $product = $this->product->pluck('name', 'id')->toArray();
            $subscription = $this->subscription->pluck('name', 'id')->toArray();
            $promotion = $this->promotion->pluck('code', 'id')->toArray();

            return view('themes.default1.order.create', compact('clients', 'product', 'subscription', 'promotion'));
        } catch (\Exception $e) {
            Bugsnag::notifyExeption($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getInstallationDetails($orderId)
    {
        $order = $this->order->findOrFail($orderId);
        $licenseStatus = StatusSetting::pluck('license_status')->first();
        $installationDetails = [];

        $cont = new \App\Http\Controllers\License\LicenseController();
        $installationDetails = $cont->searchInstallationPath($order->serial_key, $order->product);

        return \DataTables::of($installationDetails['installed_path'])
            ->addColumn('path', function ($ip) {
                // $installationDetails
                $details = getInstallationDetail($ip);
                if ($details) {
                    return $details->installation_path;
                } else {
                    return '--';
                }
            })
            ->addColumn('ip', function ($ip) {
                $ip = getInstallationDetail($ip);
                if ($ip) {
                    return $ip->installation_ip;
                } else {
                    return '--';
                }
            })
            ->addColumn('version', function ($ip) use ($order) {
                $version = getInstallationDetail($ip);
                if ($version) {
                    $versionLabel = getVersionAndLabel($version->version, $order->product);

                    return $versionLabel;
                } else {
                    return '--';
                }
            })
              ->addColumn('active', function ($ip) {
                  $version = getInstallationDetail($ip);
                  if ($version) {
                      return getDateHtml($version->updated_at).'&nbsp;'.installationStatusLabel($version->updated_at, $version->created_at);
                  } else {
                      return '--';
                  }
              })

               ->rawColumns(['path', 'ip', 'version', 'active'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function show($id)
    {
        try {
            $order = $this->order->findOrFail($id);
            if (User::onlyTrashed()->find($order->client)) {//If User is soft deleted for this order
                throw new \Exception('The user for this order is suspended from the system. Restore the user to view order details.');
            }
            $subscription = $order->subscription()->first();

            $date = '--';
            $licdate = '--';
            $supdate = '--';
            $connectionLabel = '--';
            $lastActivity = '--';
            $versionLabel = '--';
            if ($subscription) {
                $date = strtotime($subscription->update_ends_at) > 1 ? getExpiryLabel($subscription->update_ends_at) : '--';
                $licdate = strtotime($subscription->ends_at) > 1 ? getExpiryLabel($subscription->ends_at) : '--';
                $supdate = strtotime($subscription->support_ends_at) > 1 ? getExpiryLabel($subscription->support_ends_at) : '--';
            }
            $invoice = $this->invoice->where('id', $order->invoice_id)->first();

            if (! $invoice) {
                return redirect()->back()->with('fails', 'no orders');
            }
            $user = $this->user->find($invoice->user_id);
            $licenseStatus = StatusSetting::pluck('license_status')->first();
            $installationDetails = [];
            $noOfAllowedInstallation = '';
            $getInstallPreference = '';
            if ($licenseStatus == 1) {
                $cont = new \App\Http\Controllers\License\LicenseController();
                $noOfAllowedInstallation = $cont->getNoOfAllowedInstallation($order->serial_key, $order->product);
            }
            $allowDomainStatus = StatusSetting::pluck('domain_check')->first();

            return view('themes.default1.order.show',
                compact('user', 'order', 'subscription', 'licenseStatus', 'installationDetails', 'allowDomainStatus', 'noOfAllowedInstallation', 'lastActivity', 'versionLabel', 'date', 'licdate', 'supdate'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function edit($id)
    {
        try {
            $order = $this->order->where('id', $id)->first();
            $clients = $this->user->pluck('first_name', 'id')->toArray();
            $product = $this->product->pluck('name', 'id')->toArray();
            $subscription = $this->subscription->pluck('name', 'id')->toArray();
            $promotion = $this->promotion->pluck('code', 'id')->toArray();

            return view('themes.default1.order.edit',
                compact('clients', 'product', 'subscription', 'promotion', 'order'));
        } catch (\Exception $e) {
            Bugsnag::notifyExeption($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function update($id, OrderRequest $request)
    {
        try {
            $order = $this->order->where('id', $id)->first();
            $order->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $e) {
            Bugsnag::notifyExeption($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        try {
            // dd('df');
            $ids = $request->input('select');
            if (! empty($ids)) {
                foreach ($ids as $id) {
                    $order = $this->order->where('id', $id)->first();
                    if ($order) {
                        $order->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */ \Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function plan($invoice_item_id)
    {
        try {
            $planid = 0;
            $item = $this->invoice_items->find($invoice_item_id);
            if ($item) {
                $planid = $item->plan_id;
            }

            return $planid;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function checkInvoiceStatusByOrderId($orderid)
    {
        try {
            $status = 'pending';
            $order = $this->order->find($orderid);
            if ($order) {
                $invoiceid = $order->invoice_id;
                $invoice = $this->invoice->find($invoiceid);
                if ($invoice) {
                    if ($invoice->status == 'Success') {
                        $status = 'success';
                    }
                }
            }

            return $status;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function product($itemid)
    {
        $invoice_items = new InvoiceItem();
        $invoice_item = $invoice_items->find($itemid);
        $product = $invoice_item->product_name;

        return $product;
    }

    public function subscription($orderid)
    {
        $sub = $this->subscription->where('order_id', $orderid)->first();

        return $sub;
    }

    public function expiry($orderid)
    {
        $sub = $this->subscription($orderid);
        if ($sub) {
            return $sub->update_ends_at;
        }

        return '';
    }

    public function renew($orderid)
    {
        //$sub = $this->subscription($orderid);
        return url('my-orders');
    }
}
