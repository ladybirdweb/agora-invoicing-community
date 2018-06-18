<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Common\MailChimpController;
use App\Http\Controllers\Common\TemplateController;
use App\Http\Controllers\Controller;
use App\Model\Common\Setting;
use App\Model\Common\Template;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Payment\TaxByState;
use App\Model\Common\State;
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Bugsnag;
use Cart;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public $subscription;
    public $plan;
    public $templateController;
    public $product;
    public $price;
    public $user;
    public $setting;
    public $template;
    public $order;
    public $addon;
    public $invoice;
    public $invoiceItem;
    public $mailchimp;

    public function __construct()
    {
        $subscription = new Subscription();
        $this->subscription = $subscription;

        $plan = new Plan();
        $this->plan = $plan;

        $templateController = new TemplateController();
        $this->templateController = $templateController;

        $product = new Product();
        $this->product = $product;

        $price = new Price();
        $this->price = $price;

        $user = new User();
        $this->user = $user;

        $setting = new Setting();
        $this->setting = $setting;

        $template = new Template();
        $this->template = $template;

        $order = new Order();
        $this->order = $order;

        $invoice = new Invoice();
        $this->invoice = $invoice;

        $invoiceItem = new InvoiceItem();
        $this->invoiceItem = $invoiceItem;

        // $mailchimp = new MailChimpController();
        // $this->mailchimp = $mailchimp;
    }

    public function checkoutForm(Request $request)
    {
        $currency = 'INR';
        $cart_currency = 'INR';
        if (!\Auth::user()) {
            $url = $request->segments();
            $content = Cart::getContent();

            \Session::put('session-url', $url[0]);
            $domain = $request->input('domain');
            if (count($domain) > 0) {
                foreach ($domain as $key => $value) {
                    \Session::put('domain'.$key, $value);
                }
            }
            \Session::put('content', $content);

            return redirect('auth/login')->with('fails', 'Please login');
        }
        if (\Session::has('items')) {
            $content = \Session::get('items');
            $attributes = $this->getAttributes($content);
        } else {
            $content = Cart::getContent();
            $attributes = $this->getAttributes($content);
        }

        $require = [];

        //        if ($content->count() == 0) {
        //            return redirect('home');
        //        }
        if ($cart_currency != $currency) {
            return redirect('checkout');
        }
        if (count($require) > 0) {
            $this->validate($request, [
                'domain.*' => 'required',
                    ], [
                'domain.*.required' => 'Please provide Domain name',
                //'domain.*.url'      => 'Domain name is not valid',
                       ]);
        }

        try {
            $domain = $request->input('domain');
            if (count($domain) > 0) {
                foreach ($domain as $key => $value) {
                    \Session::put('domain'.$key, $value);
                }
            }
            //$content = Cart::getContent();

            return view('themes.default1.front.checkout', compact('content', 'attributes'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getAttributes($content)
    {
        foreach ($content as $key => $item) {
            $attributes[] = $item->attributes;
            $cart_currency = $attributes[0]['currency'][0]['code'];
            $user_currency = \Auth::user()->currency;
            $currency = 'INR';
            if ($user_currency == 1 || $user_currency == 'USD') {
                $currency = 'USD';
            }
            if ($cart_currency != $currency) {
                $id = $item->id;
                Cart::remove($id);
                $controller = new CartController();
                $items = $controller->addProduct($id);
                Cart::add($items);
                //
            }

            $require_domain = $this->product->where('id', $item->id)->first()->require_domain;
            if ($require_domain == 1) {
                $require[$key] = $item->id;
            }

            return $attributes;
            //$attributes[] = $item->attributes;
        }
    }

    public function payNow($invoiceid)
    {
        try {
            $invoice = $this->invoice->find($invoiceid);
            // dd($invoice);
            $items = new \Illuminate\Support\Collection();
            // dd($items);
            if ($invoice) {
                $items = $invoice->invoiceItem()->get();

                $product = $this->product($invoiceid);
            }

            return view('themes.default1.front.paynow', compact('invoice', 'items', 'product'));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postCheckout(Request $request)
    {
        $invoice_controller = new \App\Http\Controllers\Order\InvoiceController();
        $payment_method = $request->input('payment_gateway');

        $paynow = false;
        if ($request->input('invoice_id')) {
            $paynow = true;
            //$invoiceid = $request->input('invoice_id');
        }

        $cost = $request->input('cost');
        // if (\Cart::getSubTotal() > 0 || $cost > 0) {
        //     //  {
        //     $v = $this->validate($request, [
        //         'payment_gateway' => 'required',
        //             ], [

        //         'payment_gateway.required' => 'Please choose a payment gateway',
        //             ]);
        // }
        $email = \Auth::user()->email;
        $country = \Auth::user()->country;
        $stateCode = \Auth::user()->state;
        if ($country != 'IN') {
            $state = State::where('state_subdivision_code', $stateCode)->pluck('state_subdivision_name')->first();
        } else {
            $state = TaxByState::where('state_code', $stateCode)->pluck('state')->first();
        }
        $phone = \Auth::user()->mobile;
        $address = \Auth::user()->address;
        $currency = \Auth::user()->currency;
        $firstName = \Auth::user()->first_name;
        $lastName = \Auth::user()->last_name;
        $zip = \Auth::user()->zip;
        $city = \Auth::user()->town;

        try {
            if (!$this->setting->where('id', 1)->first()) {
                return redirect()->back()->with('fails', 'Complete your settings');
            }
            if ($paynow == false) {
                /*
                 * Do order, invoicing etc
                 */
                $invoice = $invoice_controller->generateInvoice();
                $status = 'pending';
                if (!$payment_method) {
                    $payment_method = 'free';
                    $status = 'success';
                }
                $invoice_no = $invoice->number;
                $date1 = new DateTime($invoice->date);
                $tz = \Auth::user()->timezone()->first()->name;
                $date1->setTimezone(new DateTimeZone($tz));
                $date = $date1->format('M j, Y, g:i a ');
                $invoiceid = $invoice->id;

                $amount = $invoice->grand_total;

                //dd($payment);
                $url = '';
                $cart = Cart::getContent();
                $invoices = $this->invoice->find($invoiceid);
                // dd($invoice);
                $items = new \Illuminate\Support\Collection();
                // dd($items);
                if ($invoices) {
                    $items = $invoice->invoiceItem()->get();
                    $product = $this->product($invoiceid);
                    $content = Cart::getContent();
                    $attributes = $this->getAttributes($content);
                }
            } else {
                $items = new \Illuminate\Support\Collection();
                $cart = [];
                $invoice_id = $request->input('invoice_id');
                $invoice = $this->invoice->find($invoice_id);
                $items = $invoice->invoiceItem()->get();
                $product = $this->product($invoice_id);
                $amount = $invoice->grand_total;
                $content = Cart::getContent();
                $attributes = $this->getAttributes($content);
            }

            //trasfer the control to event if cart price is not equal 0
            if (Cart::getSubTotal() != 0 || $cost > 0) {
                //                if ($paynow == true) {
                //                     $invoice_controller->doPayment($payment_method, $invoiceid, $amount, '', '', $status);
                //                }
                return view('themes.default1.front.postCheckout', compact('amount', 'invoice_no', ' invoiceid', ' payment_method', 'invoice', 'items', 'product', 'paynow', 'attributes'));
            // \Event::fire(new \App\Events\PaymentGateway(['request' => $request, 'cart' => Cart::getContent(), 'order' => $invoice]));
                // dd('sdfds');
            } else {
                $action = $this->checkoutAction($invoice);

                $check_product_category = $this->product($invoiceid);

                $url = '';
                if ($check_product_category->category) {
                    $url = '<div class="container">
                            
            
            <div >

            <!-- main content -->
            <div >

                            
    <div id="content" role="main">
                
           <div class="page-content">
                    <div>

    
        
            <strong>Thank you. Your Faveo Community order is confirmed. A confirmation Mail has been sent to you on your registered
                Email
            </strong><br>

            <ul class="">

                <li class="">
                    Invoice number:                    <strong>'.$invoice->number.'</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date:                    <strong>'.$date.'</strong>
                </li>

                                    <li class="woocommerce-order-overview__email email">
                        Email:                        <strong>'.$email.'</strong>
                    </li>
                
               

                                    <li class="woocommerce-order-overview__payment-method method">
                        Payment method:                        <strong>Razorpay</strong>
                    </li>
                
            </ul>

        
       
<section>
    
    <h2 style="margin-top:40px ; margin-bottom:10px;">Order Details</h2>
    
    <table class="table table-bordered table-striped">
    
        <thead>
            <tr>
                <th>Product</th>
                <th>Total</th>
            </tr>
        </thead>
        
        <tbody>
            <tr>

    <td>
        <strong>'.$product->name.' ×   '.$items[0]->quantity.' </strong>
    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$attributes[0]['currency'][0]['symbol'].'</span> '.$invoice->grand_total.'</span>    </td>

</tr>

        </tbody>
        <tfoot>
                                <tr>
                        <th scope="row">Invoice No:</th>
                        <td><span class="woocommerce-Price-amount amount"> '.$invoice->number.'</span></td>
                    </tr>
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>
                                        <tr>
                        <th scope="row">Total:</th>
                            <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$attributes[0]['currency'][0]['symbol'].'</span> '.$invoice->grand_total.'</span></td>
                    </tr>
                            </tfoot>
    </table>
    <br>
    
            <section class="woocommerce-customer-details">

    
    <h2 style="margin-bottom:20px;">Billing address</h2>

    <strong>
       '.$firstName.' '.$lastName.'<br>'.$address.'<br>'.$city.' - '.$zip.'<br> '.$state.' <br>
                   '.$phone.' <br><br>
                     <a href= product/download/'.$product->id.'/'.$invoice->number.' " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
            </strong>

    
</section>
    

</section>

    

</div>
                </div>
           

        
    </div>

        

</div>

    
    </div>
    </div>';
                }
                \Cart::clear();

                return redirect()->back()->with('success', $url);
            }
        } catch (\Exception $ex) {
            dd($ex);
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkoutAction($invoice)
    {
        try {
            //get elements from invoice
            $invoice_number = $invoice->number;
            $invoice_id = $invoice->id;
            $invoice->status = 'success';
            $invoice->save();
            //dd($invoice->id);

            $invoice_items = $this->invoiceItem->where('invoice_id', $invoice->id)->first();
            $product = $invoice_items->product_name;

            $user_id = \Auth::user()->id;

            $url = '';
            $check_product_category = $this->product($invoice_id);
            if ($check_product_category->category) {
                $url = url("download/$user_id/$invoice_number");
                //execute the order
                $order = new \App\Http\Controllers\Order\OrderController();
                $order->executeOrder($invoice->id, $order_status = 'executed');
                $payment = new \App\Http\Controllers\Order\InvoiceController();
                $payment->postRazorpayPayment($invoice_id, $invoice->grand_total);
            }

            return 'success';
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function product($invoiceid)
    {
        try {
            $invoice = $this->invoiceItem->where('invoice_id', $invoiceid)->first();
            // dd($invoice);
            $name = $invoice->product_name;
            $product = $this->product->where('name', $name)->first();

            return $product;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function GenerateOrder()
    {
        try {
            $products = [];
            $items = \Cart::getContent();
            foreach ($items as $item) {

               //this is product
                $id = $item->id;
                $this->AddProductToOrder($id);
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not Generate Order');
        }
    }

    public function AddProductToOrder($id)
    {
        try {
            $cart = \Cart::get($id);
            $client = \Auth::user()->id;
            $payment_method = \Input::get('payment_gateway');
            $promotion_code = '';
            $order_status = 'pending';
            $serial_key = '';
            $product = $id;
            $addon = '';
            $domain = '';
            $price_override = $cart->getPriceSumWithConditions();
            $qty = $cart->quantity;

            $planid = $this->price->where('product_id', $id)->first()->subscription;

            $or = $this->order->create(['client' => $client, 'payment_method' => $payment_method, 'promotion_code' => $promotion_code, 'order_status' => $order_status, 'serial_key' => $serial_key, 'product' => $product, 'addon' => $addon, 'domain' => $domain, 'price_override' => $price_override, 'qty' => $qty]);

            $this->AddSubscription($or->id, $planid);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not Generate Order for Product');
        }
    }

    public function AddSubscription($orderid, $planid)
    {
        try {
            $days = $this->plan->where('id', $planid)->first()->days;
            //dd($days);
            if ($days > 0) {
                $dt = \Carbon\Carbon::now();
                //dd($dt);
                $user_id = \Auth::user()->id;
                $ends_at = $dt->addDays($days);
            } else {
                $ends_at = '';
            }
            $this->subscription->create(['user_id' => \Auth::user()->id, 'plan_id' => $planid, 'order_id' => $orderid, 'ends_at' => $ends_at]);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not Generate Subscription');
        }
    }

    public function GenerateInvoice()
    {
        try {
            $user_id = \Auth::user()->id;
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $grand_total = \Cart::getSubTotal();

            $invoice = $this->invoice->create(['user_id' => $user_id, 'number' => $number, 'date' => $date, 'grand_total' => $grand_total]);
            foreach (\Cart::getContent() as $cart) {
                $this->CreateInvoiceItems($invoice->id, $cart);
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not Generate Invoice');
        }
    }

    public function CreateInvoiceItems($invoiceid, $cart)
    {
        try {
            $product_name = $cart->name;
            $regular_price = $cart->price;
            $quantity = $cart->quantity;
            $subtotal = $cart->getPriceSumWithConditions();

            $tax_name = '';
            $tax_percentage = '';

            foreach ($cart->attributes['tax'] as $tax) {
                //dd($tax['name']);
                $tax_name .= $tax['name'].',';
                $tax_percentage .= $tax['rate'].',';
            }

            //dd($tax_name);

            $invoiceItem = $this->invoiceItem->create(['invoice_id' => $invoiceid, 'product_name' => $product_name, 'regular_price' => $regular_price, 'quantity' => $quantity, 'tax_name' => $tax_name, 'tax_percentage' => $tax_percentage, 'subtotal' => $subtotal]);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('Can not create Invoice Items');
        }
    }
}
