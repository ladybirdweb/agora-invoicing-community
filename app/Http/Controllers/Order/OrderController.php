<?php

namespace App\Http\Controllers\Order;

use App\Http\Requests\Order\OrderRequest;
use App\Model\Common\StatusSetting;
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
use Carbon\Carbon;
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
        $this->middleware('admin');

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
        try {
            $products = $this->product->where('id', '!=', 1)->pluck('name', 'id')->toArray();

            $paidUnpaidOptions = ['paid'=>'Paid Products', 'unpaid'=>'Unpaid Products'];
            $insNotIns = ['installed'=>'Yes', 'not_installed'=>'No'];
            $activeInstallationOptions = ['paid_ins'=>'For Paid Products', 'unpaid_ins'=>'For Unpaid Products', 'all_ins'=>'All Products'];
            $inactiveInstallationOptions = ['paid_inactive_ins'=>'For Paid Products', 'unpaid_inactive_ins'=>'For Unpaid Products', 'all_inactive_ins'=>'All Products'];
            $renewal = ['expired_subscription'=>'Expired Subscriptions', 'active_subscription'=> 'Active Subscriptions'];
            $allVersions = Subscription::where('version', '!=', '')->whereNotNull('version')
                ->orderBy('version', 'desc')->groupBy('version')
                ->pluck('version')->toArray();

            return view('themes.default1.order.index',
                compact('request', 'products', 'allVersions', 'activeInstallationOptions', 'paidUnpaidOptions', 'inactiveInstallationOptions', 'renewal', 'insNotIns'));
        } catch (\Exception $e) {
            Bugsnag::notifyExeption($e);

            return redirect('orders')->with('fails', $e->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        $query = $this->advanceSearch($request);

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
                return getVersionAndLabel($model->product_version, $model->product);
            })
            ->addColumn('number', function ($model) {
                $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>';

                if ($model->updated_at) {//For few older clients subscription was not generated, so no updated_at column exists
                    $orderLink = '<a href='.url('orders/'.$model->id).'>'.$model->number.'</a>'.$this->installationStatusLabel($model->updated_at);
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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Response
     */
    public function show($id)
    {
        try {
            $order = $this->order->findOrFail($id);
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
                $lastActivity = getDateHtml($subscription->updated_at).'&nbsp;'.$this->installationStatusLabel($subscription->updated_at);
                $versionLabel = getVersionAndLabel($subscription->version, $order->product);
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
                $installationDetails = $cont->searchInstallationPath($order->serial_key, $order->product);
                $noOfAllowedInstallation = $cont->getNoOfAllowedInstallation($order->serial_key, $order->product);
                $getInstallPreference = $cont->getInstallPreference($order->serial_key, $order->product);
            }

            $allowDomainStatus = StatusSetting::pluck('domain_check')->first();

            return view('themes.default1.order.show',
                compact('user', 'order', 'subscription', 'licenseStatus', 'installationDetails', 'allowDomainStatus', 'noOfAllowedInstallation', 'getInstallPreference', 'lastActivity', 'versionLabel', 'date', 'licdate', 'supdate'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function installationStatusLabel($lastConnectionDate)
    {
        return $lastConnectionDate->toDateTimeString() > (new Carbon('-30 days'))->toDateTimeString() ? "&nbsp;<span class='badge badge-primary' style='background-color:darkcyan !important;' <label data-toggle='tooltip' style='font-weight:500;' data-placement='top' title='Installation is Active'>
                     </label>Active</span>" : "&nbsp;<span class='badge badge-info' <label data-toggle='tooltip' style='font-weight:500;background-color:crimson;' data-placement='top' title='Installation inactive for more than 30 days'>
                    </label>Inactive</span>";
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
            Bugsnag::notifyException($ex);

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
            Bugsnag::notifyException($ex);

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
