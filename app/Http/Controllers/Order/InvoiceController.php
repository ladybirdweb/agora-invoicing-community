<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Front\CartController;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Promotion;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxByState;
use App\Model\Payment\TaxOption;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Traits\CoupCodeAndInvoiceSearch;
use App\User;
use Bugsnag;
use Illuminate\Http\Request;
use Input;
use Log;

class InvoiceController extends TaxRatesAndCodeExpiryController
{
    use  CoupCodeAndInvoiceSearch;

    public $invoice;
    public $invoiceItem;
    public $user;
    public $template;
    public $setting;
    public $payment;
    public $product;
    public $price;
    public $promotion;
    public $currency;
    public $tax;
    public $tax_option;
    public $order;
    public $cartController;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['pdf']]);

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $invoiceItem = new InvoiceItem();
        $this->invoiceItem = $invoiceItem;

        $user = new User();
        $this->user = $user;

        $template = new Template();
        $this->template = $template;

        $seting = new Setting();
        $this->setting = $seting;

        $payment = new Payment();
        $this->payment = $payment;

        $product = new Product();
        $this->product = $product;

        $price = new Price();
        $this->price = $price;

        $promotion = new Promotion();
        $this->promotion = $promotion;

        $currency = new Currency();
        $this->currency = $currency;

        $tax = new Tax();
        $this->tax = $tax;

        $tax_option = new TaxOption();
        $this->tax_option = $tax_option;

        $order = new Order();
        $this->order = $order;

        $tax_by_state = new TaxByState();
        $this->tax_by_state = new $tax_by_state();

        $cartController = new CartController();
        $this->cartController = $cartController;
    }

    public function index(Request $request)
    {
        try {
            $currencies = Currency::where('status', 1)->pluck('code')->toArray();
            $name = $request->input('name');
            $invoice_no = $request->input('invoice_no');
            $status = $request->input('status');

            $currency_id = $request->input('currency_id');
            $from = $request->input('from');
            $till = $request->input('till');

            return view('themes.default1.invoice.index', compact('name','invoice_no','status','currencies','currency_id','from',

                'till'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getInvoices(Request $request)
    {
        $name = $request->input('name');
        $invoice_no = $request->input('invoice_no');
        $status = $request->input('status');
        $currency = $request->input('currency_id');
        $from = $request->input('from');
        $till = $request->input('till');
        $query = $this->advanceSearch($name, $invoice_no, $currency, $status, $from, $till);

        return \DataTables::of($query->take(100))
         ->setTotalRecords($query->count())

         ->addColumn('checkbox', function ($model) {
             return "<input type='checkbox' class='invoice_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
         })
                        ->addColumn('user_id', function ($model) {
                            $first = $this->user->where('id', $model->user_id)->first()->first_name;
                            $last = $this->user->where('id', $model->user_id)->first()->last_name;
                            $id = $this->user->where('id', $model->user_id)->first()->id;

                            return '<a href='.url('clients/'.$id).'>'.ucfirst($first).' '.ucfirst($last).'</a>';
                        })
                         ->addColumn('number', function ($model) {
                             return ucfirst($model->number);
                         })

                        ->addColumn('date', function ($model) {
                            $date = ($model->created_at);

                            return $date;
                            // return "<span style='display:none'>$model->id</span>".$date->format('l, F j, Y H:m');
                        })
                         ->addColumn('grand_total', function ($model) {
                             return ucfirst($model->number);
                         })
                          ->addColumn('status', function ($model) {
                              return ucfirst($model->status);
                          })

                        ->addColumn('action', function ($model) {
                            $action = '';

                            $check = $this->checkExecution($model->id);
                            if ($check == false) {
                                $action = '<a href='.url('order/execute?invoiceid='.$model->id)
                                ." class='btn btn-sm btn-primary btn-xs'>
                                <i class='fa fa-tasks' style='color:white;'>
                                 </i>&nbsp;&nbsp; Execute Order</a>";
                            }

                            return '<a href='.url('invoices/show?invoiceid='.$model->id)
                            ." class='btn btn-sm btn-primary btn-xs'><i class='fa fa-eye' 
                            style='color:white;'> </i>&nbsp;&nbsp;View</a>"
                                    ."   $action";
                        })
                         ->filterColumn('user_id', function ($query, $keyword) {
                             $sql = 'first_name like ?';
                             $query->whereRaw($sql, ["%{$keyword}%"]);
                         })

                          ->filterColumn('status', function ($query, $keyword) {
                              $sql = 'status like ?';
                              $query->whereRaw($sql, ["%{$keyword}%"]);
                          })

                        ->filterColumn('number', function ($query, $keyword) {
                            $sql = 'number like ?';
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                         ->filterColumn('grand_total', function ($query, $keyword) {
                             $sql = 'grand_total like ?';
                             $query->whereRaw($sql, ["%{$keyword}%"]);
                         })
                          ->filterColumn('date', function ($query, $keyword) {
                              $sql = 'date like ?';
                              $query->whereRaw($sql, ["%{$keyword}%"]);
                          })

                         ->rawColumns(['checkbox', 'user_id', 'number', 'date', 'grand_total', 'status', 'action'])
                        ->make(true);
    }

    /**
     * Shoe Invoice when view Invoice is selected from dropdown in Admin Panel
     *
     * @param  Request $request   Get InvoiceId as Request
     *
     */
    public function show(Request $request)
    {
        try {
            $id = $request->input('invoiceid');
            $invoice = $this->invoice->where('id', $id)->first();
            $invoiceItems = $this->invoiceItem->where('invoice_id', $id)->get();
            $user = $this->user->find($invoice->user_id);
            $currency = CartController::currency($user);
            $symbol =  $currency['symbol'];
            return view('themes.default1.invoice.show', compact('invoiceItems', 'invoice', 'user','currency','symbol'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * not in use case.
     *
     * @param Request $request
     *
     * @return type
     */
    public function generateById(Request $request)
    {
        try {
            $clientid = $request->input('clientid');
            $user = new User();
            if ($clientid) {
                $user = $user->where('id', $clientid)->first();
                if (!$user) {
                    return redirect()->back()->with('fails', 'Invalid user');
                }
            } else {
                $user = '';
            }
            $products = $this->product->where('id', '!=', 1)->pluck('name', 'id')->toArray();
            $currency = $this->currency->pluck('name', 'code')->toArray();

            return view('themes.default1.invoice.generate', compact('user', 'products', 'currency'));
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
 
 
    public function sendmailClientAgent($userid, $invoiceid)
    {
        try {
            $agent = \Input::get('agent');
            $client = \Input::get('client');
            if ($agent == 1) {
                $id = \Auth::user()->id;
                $this->sendMail($id, $invoiceid);
            }
            if ($client == 1) {
                $this->sendMail($userid, $invoiceid);
            }
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Generate invoice.
     *
     * @throws \Exception
     */
    
    //Is this Method only for cliet?? because Auth::user->id?
    public function generateInvoice()
    {
        try {
            $tax_rule = new \App\Model\Payment\TaxOption();
            $rule = $tax_rule->findOrFail(1);
            $rounding = $rule->rounding;
            $user_id = \Auth::user()->id;
            if (\Auth::user()->currency == 'INR') {
                $grand_total = \Cart::getSubTotal();
            } else {
                foreach (\Cart::getContent() as $cart) {
                    $grand_total = \Cart::getSubTotal();
                }
            }
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            if ($rounding == 1) {
                $grand_total = round($grand_total);
            }
            $content = \Cart::getContent();
            $attributes = [];
            foreach ($content as $key => $item) {
                $attributes[] = $item->attributes;
            }
            $symbol = $attributes[0]['currency']['symbol'];
           $invoice = $this->invoice->create(['user_id' => $user_id, 'number' => $number,
             'date'=> $date, 'grand_total' => $grand_total, 'status' => 'pending',
             'currency'                                  => $symbol, ]);
            foreach (\Cart::getContent() as $cart) {
                $this->createInvoiceItems($invoice->id, $cart);
            }
            $this->sendMail($user_id, $invoice->id);
            return $invoice;
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not Generate Invoice');
        }
    }

    public function createInvoiceItems($invoiceid, $cart)
    {
        try {
            $planid = 0;
            $product_name = $cart->name;
            $regular_price = $cart->price;
            $quantity = $cart->quantity;
            $agents = $cart->attributes->agents;
            $domain = $this->domain($cart->id);
            $cart_cont = new \App\Http\Controllers\Front\CartController();
            if ($cart_cont->checkPlanSession() === true) {
                $planid = \Session::get('plan');
            }
            if ($planid == 0) {
                //When Product is added from Faveo Website
                $planid = Plan::where('product', $cart->id)->pluck('id')->first();
            }
            $user_currency = \Auth::user()->currency;
            $subtotal = $this->getSubtotal($user_currency, $cart);

            $tax_name = '';
            $tax_percentage = '';

            foreach ($cart->attributes['tax'] as $tax) {
                $tax_name .= $tax['name'].',';
                $tax_percentage .= $tax['rate'].',';
            }
            $invoiceItem = $this->invoiceItem->create([
                'invoice_id'     => $invoiceid,
                'product_name'   => $product_name,
                'regular_price'  => $regular_price,
                'quantity'       => $quantity,
                'tax_name'       => $tax_name,
                'tax_percentage' => $tax_percentage,
                'subtotal'       => $subtotal,
                'domain'         => $domain,
                'plan_id'        => $planid,
                'agents'         => $agents, 
            ]);
            return $invoiceItem;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            throw new \Exception('Can not create Invoice Items');
        }
    }

    public function invoiceGenerateByForm(Request $request, $user_id = '')
    {
       try {
             $agents = $request->input('agents');
             $qty = $request->input('quantity');
            if ($user_id == '') {
                $user_id = \Request::input('user');
            }
            $productid = $request->input('product');
             $plan = $request->input('plan');
            if(!$agents){//If agents is not received in the request in the case when 'modify agent' is not allowed for the Product,get the no of Agents from the Plan Table.
             $planForAgent = Product::find($productid)->planRelation->find($plan);
             if($planForAgent) {//If Plan Exists For the Product ie not a Product without Plan
             $noOfAgents = $planForAgent->planPrice->first()->no_of_agents;
             $agents = $noOfAgents ? $noOfAgents : 0; //If no. of Agents is specified then that,else 0(Unlimited Agents) 
             } else{
                  $agents = 0;
             }
            
        }
        if(!$qty) {//If quantity is not received in the request in the case when 'modify quantity' is not allowed for the Product,get the Product qUANTITY from the Plan Table.
             $planForQty = Product::find($productid)->planRelation->find($plan);
             if($planForQty){
             $quantity = Product::find($productid)->planRelation->find($plan)->planPrice->first()->product_quantity;
             $qty = $quantity ? $quantity : 1; //If no. of Agents is specified then that,else 0(Unlimited Agents)  
             } else{
                $qty = 1;
             }
           
        }
            $code = $request->input('code');
            $total = $request->input('price');
            $description = $request->input('description');
            if ($request->has('domain')) {
                $domain = $request->input('domain');
                $this->setDomain($productid, $domain);
            }
            $controller = new \App\Http\Controllers\Front\CartController();
            $userCurrency = $controller->currency($user_id);
            $currency = $userCurrency['currency'];
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $product = Product::find($productid);
            $cost = $controller->cost($productid, $user_id, $plan);
            if ($cost != $total) {
                $grand_total = $total;
            }
            $grand_total = $this->getGrandTotal($code, $total, $cost, $productid, $currency);
            $grand_total = $qty * $grand_total;

            $tax = $this->checkTax($product->id, $user_id);
            $tax_name = '';
            $tax_rate = '';
            if (!empty($tax)) {
                $tax_name = $tax[0];
                $tax_rate = $tax[1];
            }

            $grand_total = $this->calculateTotal($tax_rate, $grand_total);
            $grand_total = \App\Http\Controllers\Front\CartController::rounding($grand_total);

            $invoice = Invoice::create(['user_id' => $user_id,
                'number'                          => $number, 'date' => $date, 'grand_total' => $grand_total,
                'currency'                        => $currency, 'status' => 'pending', 'description' => $description, ]);

            $items = $this->createInvoiceItemsByAdmin($invoice->id, $productid,
             $code, $total, $currency, $qty,$agents, $plan, $user_id, $tax_name, $tax_rate);
            $result = $this->getMessage($items, $user_id);
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);
            $result = ['fails' => $ex->getMessage()];
        }

        return response()->json(compact('result'));
    }

    public function doPayment($payment_method, $invoiceid, $amount,
        $parent_id = '', $userid = '', $payment_status = 'pending')
    {
        try {
            if ($amount > 0) {
                if ($userid == '') {
                    $userid = \Auth::user()->id;
                }
                if ($amount == 0) {
                    $payment_status = 'success';
                }
                $this->payment->create([
                    'parent_id'      => $parent_id,
                    'invoice_id'     => $invoiceid,
                    'user_id'        => $userid,
                    'amount'         => $amount,
                    'payment_method' => $payment_method,
                    'payment_status' => $payment_status,
                ]);
                $this->updateInvoice($invoiceid);
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function createInvoiceItemsByAdmin($invoiceid, $productid, $code, $price,
        $currency, $qty,$agents,$planid = '', $userid = '', $tax_name = '', $tax_rate = '')
    {
        try {
            $discount = '';
            $mode = '';
            $product = $this->product->findOrFail($productid);
            $plan = Plan::where('product', $productid)->first();
            $subtotal = $qty * intval($price);
            if ($code) {
                $subtotal = $this->checkCode($code, $productid, $currency);
                $mode = 'coupon';
                $discount = $price - $subtotal;
            }
            $userid = \Auth::user()->id;
            if (\Auth::user()->role == 'user') {
                $tax = $this->checkTax($product->id, $userid);
                $tax_name = '';
                $tax_rate = '';
                if (!empty($tax)) {
                    $tax_name = $tax[0];
                    $tax_rate = $tax[1];
                }
            }

            $subtotal = $this->calculateTotal($tax_rate, $subtotal);

            $domain = $this->domain($productid);
            $items = $this->invoiceItem->create([
                'invoice_id'     => $invoiceid,
                'product_name'   => $product->name,
                'regular_price'  => $price,
                'quantity'       => $qty,
                'discount'       => $discount,
                'discount_mode'  => $mode,
                'subtotal'       => \App\Http\Controllers\Front\CartController::rounding($subtotal),
                'tax_name'       => $tax_name,
                'tax_percentage' => $tax_rate,
                'domain'         => $domain,
                'plan_id'        => $planid,
                'agents'         => $agents,
            ]);

            return $items;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
             return redirect()->back()->with('fails', $ex->getMessage());
        }
    }



    public function checkTax($productid, $userid)
    {
        try {
            $taxs = [];
            $taxs[0] = ['name' => 'null', 'rate' => 0];
            $geoip_state = User::where('id', $userid)->pluck('state')->first();
            $geoip_country = User::where('id', $userid)->pluck('country')->first();
            $product = $this->product->findOrFail($productid);
            $cartController = new CartController();
            if ($this->tax_option->findOrFail(1)->inclusive == 0) {
                if ($this->tax_option->findOrFail(1)->tax_enable == 1) {
                    $taxs = $this->getTaxWhenEnable($productid, $taxs[0], $userid);
                } elseif ($this->tax_option->tax_enable == 0) {//if tax_enable is 0

                    $taxClassId = Tax::where('country', '')->where('state', 'Any State')
                     ->pluck('tax_classes_id')->first(); //In case of India when
                    //other tax is available and tax is not enabled
                    if ($taxClassId) {
                        $rate = $this->getTotalRate($taxClassId, $productid, $taxs);
                        $taxs = $rate['taxes'];
                        $rate = $rate['rate'];
                    } elseif ($geoip_country != 'IN') {//In case of other country
                        // when tax is available and tax is not enabled(Applicable
                        //when Global Tax class for any country and state is not there)

                        $taxClassId = Tax::where('state', $geoip_state)
                        ->orWhere('country', $geoip_country)->pluck('tax_classes_id')->first();
                        if ($taxClassId) { //if state equals the user State
                            $rate = $this->getTotalRate($taxClassId, $productid, $taxs);
                            $taxs = $rate['taxes'];
                            $rate = $rate['rate'];
                        }
                        $taxs = ([$taxs[0]['name'], $taxs[0]['rate']]);

                        return $taxs;
                    }
                    $taxs = ([$taxs[0]['name'], $taxs[0]['rate']]);
                } else {
                    $taxs = ([$taxs[0]['name'], $taxs[0]['rate']]);
                }
            }

            return $taxs;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.check-tax-error'));
        }
    }

    public function getRate($productid, $taxs, $userid)
    {
        $tax_attribute = [];
        $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
        $tax_value = '0';

        $geoip_state = User::where('id', $userid)->pluck('state')->first();
        $geoip_country = User::where('id', $userid)->pluck('country')->first();
        $user_state = $this->tax_by_state::where('state_code', $geoip_state)->first();
        $origin_state = $this->setting->first()->state; //Get the State of origin
        $cartController = new CartController();
        $rate = 0;
        $name1 = 'CGST';
        $name2 = 'SGST';
        $name3 = 'IGST';
        $name4 = 'UTGST';
        $c_gst = 0;
        $s_gst = 0;
        $i_gst = 0;
        $ut_gst = 0;
        $state_code = '';
        if ($user_state != '') {//Get the CGST,SGST,IGST,STATE_CODE of the user
            $tax = $this->getTaxWhenState($user_state, $productid, $origin_state);
            $taxes = $tax['taxes'];
            $value = $tax['value'];
        } else {//If user from other Country
            $tax = $this->getTaxWhenOtherCountry($geoip_state, $geoip_country, $productid);
            $taxes = $tax['taxes'];
            $value = $tax['value'];
            $rate = $tax['rate'];
        }

        foreach ($taxes as $key => $tax) {
            if ($taxes[0]) {
                $tax_attribute[$key] = ['name' => $tax->name, 'name1' => $name1,
                 'name2'                       => $name2, 'name3' => $name3, 'name4' => $name4,
                 'rate'                        => $value, 'rate1'=>$c_gst, 'rate2'=>$s_gst,
                 'rate3'                       => $i_gst, 'rate4'=>$ut_gst, 'state'=>$state_code,
                  'origin_state'               => $origin_state, ];

                $rate = $tax->rate;

                $tax_value = $value;
            } else {
                $tax_attribute[0] = ['name' => 'null', 'rate' => 0, 'tax_enable' =>0];
                $tax_value = '0%';
            }
        }

        return ['taxs'=>$tax_attribute, 'value'=>$tax_value];
    }





    public function pdf(Request $request)
    {
        try {
            $id = $request->input('invoiceid');
            if (!$id) {
                return redirect()->back()->with('fails', \Lang::get('message.no-invoice-id'));
            }
            $invoice = $this->invoice->where('id', $id)->first();
            if (!$invoice) {
                return redirect()->back()->with('fails', \Lang::get('message.invalid-invoice-id'));
            }
            $invoiceItems = $this->invoiceItem->where('invoice_id', $id)->get();
            if ($invoiceItems->count() == 0) {
                return redirect()->back()->with('fails', \Lang::get('message.invalid-invoice-id'));
            }
            $user = $this->user->find($invoice->user_id);
            if (!$user) {
                return redirect()->back()->with('fails', 'No User');
            }
           $cont = new \App\Http\Controllers\Front\CartController();
           $currency = $cont->currency($user);
           $symbol =  $currency['currency'];
            $pdf = \PDF::loadView('themes.default1.invoice.newpdf', compact('invoiceItems', 'invoice', 'user','currency','symbol'));
            return $pdf->download($user->first_name.'-invoice.pdf');
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }


    public function payment(Request $request)
    {
        try {
            if ($request->has('invoiceid')) {
                $invoice_id = $request->input('invoiceid');
                $invoice = $this->invoice->find($invoice_id);
                $userid = $invoice->user_id;
                //dd($invoice);
                $invoice_status = '';
                $payment_status = '';
                $payment_method = '';
                $domain = '';
                if ($invoice) {
                    $invoice_status = $invoice->status;
                    $items = $invoice->invoiceItem()->first();
                    if ($items) {
                        $domain = $items->domain;
                    }
                }
                $payment = $this->payment->where('invoice_id', $invoice_id)->first();
                if ($payment) {
                    $payment_status = $payment->payment_status;
                    $payment_method = $payment->payment_method;
                }

                return view('themes.default1.invoice.payment',
                 compact('invoice_status', 'payment_status',
                  'payment_method', 'invoice_id', 'domain', 'invoice', 'userid'));
            }

            return redirect()->back();
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function setDomain($productid, $domain)
    {
        try {
            if (\Session::has('domain'.$productid)) {
                \Session::forget('domain'.$productid);
            }
            \Session::put('domain'.$productid, $domain);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }



    public function sendMail($userid, $invoiceid)
    {
        try {
            $invoice = $this->invoice->find($invoiceid);
            $number = $invoice->number;
            $total = $invoice->grand_total;

            return $this->sendInvoiceMail($userid, $number, $total, $invoiceid);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function deletePayment(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $payment = $this->payment->where('id', $id)->first();
                    if ($payment) {
                        $invoice = $this->invoice->find($payment->invoice_id);
                        if ($invoice) {
                            $invoice->status = 'pending';
                            $invoice->save();
                        }
                        $payment->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!</b> 
                    './* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */ \Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }
}
