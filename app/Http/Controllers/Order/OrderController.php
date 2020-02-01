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
use Illuminate\Http\Request;

class OrderController extends BaseOrderController
{
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
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        try {
            $products = $this->product->where('id', '!=', 1)->pluck('name', 'id')->toArray();
            $order_no = $request->input('order_no');
            $product_id = $request->input('product_id');
            $expiry = $request->input('expiry');
            $expiryTill = $request->input('expiryTill');
            $from = $request->input('from');
            $till = $request->input('till');
            $domain = $request->input('domain');
            $paidUnpaid = $request->input('p_un');
            $paidUnpaid = $request->input('p_un');
            $allInstallation = $request->input('act_ins');
            $version = $request->input('version');
            return view('themes.default1.order.index',
                compact('products', 'order_no', 'product_id',
                    'expiry', 'from', 'till', 'domain', 'expiryTill', 'paidUnpaid', 'allInstallation','version'));
        } catch (\Exception $e) {
            Bugsnag::notifyExeption($e);

            return redirect('orders')->with('fails', $e->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        $order_no = $request->input('order_no');
        $product_id = $request->input('product_id');
        $expiry = $request->input('expiry');
        $expiryTill = $request->input('expiryTill');
        $from = $request->input('from');
        $till = $request->input('till');
        $domain = $request->input('domain');
        $paidUnpaid = $request->input('p_un');
        $allInstallation = $request->input('act_ins');
        $version = $request->input('version');
        $query = $this->advanceSearch($order_no, $product_id, $expiry, $expiryTill, $from, $till, $domain, $paidUnpaid, $allInstallation, $version);

        return \DataTables::of($query)
                        ->setTotalRecords($query->count())
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='order_checkbox' value=".
                            $model->id.' name=select[] id=check>';
                        })
                        ->addColumn('date', function ($model) {
                            $date = $model->created_at;

                            return "<span style='display:none'>$model->id</span>".$date;
                        })
                        ->addColumn('client', function ($model) {
                            $user = $this->user->where('id', $model->client)->first();
                            $first = $user->first_name;
                            $last = $user->last_name;
                            $id = $user->id;

                            return '<a href='.url('clients/'.$id).'>'.ucfirst($first).' '.ucfirst($last).'<a>';
                        })
                        ->addColumn('productname', function ($model) {
                            $productid = ($model->product);
                            $productName = Product::where('id', $productid)->pluck('name')->first();

                            return $productName;
                        })
                        ->addColumn('number', function ($model) {
                            return ucfirst($model->number);
                        })
                        ->addColumn('price_override', function ($model) {
                            $currency = $model->user()->find($model->client)->currency;

                            return currency_format($model->price_override, $code = $currency);
                        })
                        ->addColumn('order_status', function ($model) {
                            return ucfirst($model->order_status);
                        })
                        // ->showColumns('number', 'price_override', 'order_status')
                        ->addColumn('update_ends_at', function ($model) {
                            $end = $this->getEndDate($model);

                            return $end;
                        })
                        ->addColumn('action', function ($model) {
                            $sub = $model->subscription()->first();
                            $status = $this->checkInvoiceStatusByOrderId($model->id);
                            $url = $this->getUrl($model, $status, $sub);

                            return $url;
                        })

                         ->filterColumn('created_at', function ($query, $keyword) {
                             $sql = 'created_at like ?';
                             $query->whereRaw($sql, ["%{$keyword}%"]);
                         })

                          ->filterColumn('client', function ($query, $keyword) {
                              $sql = 'client like ?';
                              $query->whereRaw($sql, ["%{$keyword}%"]);
                          })

                           ->filterColumn('number', function ($query, $keyword) {
                               $sql = 'number like ?';
                               $query->whereRaw($sql, ["%{$keyword}%"]);
                           })
                            ->filterColumn('price_override', function ($query, $keyword) {
                                $sql = 'price_override like ?';
                                $query->whereRaw($sql, ["%{$keyword}%"]);
                            })
                             ->filterColumn('order_status', function ($query, $keyword) {
                                 $sql = 'order_status like ?';
                                 $query->whereRaw($sql, ["%{$keyword}%"]);
                             })

                              ->filterColumn('update_ends_at', function ($query, $keyword) {
                                  $sql = 'update_ends_at like ?';
                                  $query->whereRaw($sql, ["%{$keyword}%"]);
                              })

                         ->rawColumns(['checkbox', 'date', 'client', 'number',
                          'price_override', 'order_status', 'productname', 'update_ends_at', 'action', ])
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
            $currenctVersion = $subscription->version;
            $lastActivity = $subscription->updated_at;
            $invoiceid = $order->invoice_id;
            $invoice = $this->invoice->where('id', $invoiceid)->first();
            if (!$invoice) {
                return redirect()->back()->with('fails', 'no orders');
            }
            $invoiceItems = $this->invoice_items->where('invoice_id', $invoiceid)->get();
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
                compact('invoiceItems', 'invoice', 'user', 'order', 'subscription', 'licenseStatus', 'installationDetails', 'allowDomainStatus', 'noOfAllowedInstallation', 'getInstallPreference', 'currenctVersion', 'lastActivity'));
        } catch (\Exception $ex) {
            dd($ex);
            Bugsnag::notifyException($ex);

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
            if (!empty($ids)) {
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
