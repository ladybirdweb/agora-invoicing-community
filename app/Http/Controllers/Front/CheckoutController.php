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
use App\Model\Product\Price;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Cart;
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

        $mailchimp = new MailChimpController();
        $this->mailchimp = $mailchimp;
    }

    public function CheckoutForm(Request $request)
    {
        if (!\Auth::user()) {
            $url = $request->segments();

            \Session::put('session-url', $url[0]);

            return redirect('auth/login')->with('fails', 'Please login');
        }
        $content = Cart::getContent();
        $require = [];
        foreach ($content as $key => $item) {
            $attributes[] = $item->attributes;
            $require_domain = $this->product->where('id', $item->id)->first()->require_domain;
            if ($require_domain == 1) {
                $require[$key] = $item->id;
            }
        }
        if (count($require) > 0) {
            $this->validate($request, [
                'domain.*' => 'required|url',
                    ], [
                'domain.*.required' => 'Please provide Domain name',
                'domain.*.url'      => 'Domain name is not valid',
            ]);
        }
        try {
            $domain = $request->input('domain');
            if (count($domain) > 0) {
                foreach ($domain as $key => $value) {
                    \Session::put('domain'.$key, $value);
                }
            }

            return view('themes.default1.front.checkout', compact('content', 'attributes'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postCheckout(Request $request)
    {
        if (\Cart::getSubTotal() > 0) {
            $v = $this->validate($request, [
                'payment_gateway' => 'required',
                    ], [

                'payment_gateway.required' => 'Please choose a payment gateway',
            ]);
        }
        try {
            if (!$this->setting->where('id', 1)->first()) {
                return redirect()->back()->with('fails', 'Complete your settings');
            }
            /*
             * Do order, invoicing etc
             */
            $invoice_controller = new \App\Http\Controllers\Order\InvoiceController();
            $invoice = $invoice_controller->GenerateInvoice();
            $payment_method = $request->input('payment_gateway');
            $status = 'pending';
            if (!$payment_method) {
                $payment_method = 'free';
                $status = 'success';
            }
            $invoiceid = $invoice->id;
            $amount = $invoice->grand_total;

            //dd($payment);
            $url = '';
            //trasfer the control to event if cart price is not equal 0
            if (Cart::getSubTotal() != 0) {

                //$invoice_controller->doPayment($payment_method, $invoiceid, $amount,'','',$status);
                \Event::fire(new \App\Events\PaymentGateway(['request' => $request, 'cart' => Cart::getContent(), 'order' => $invoice]));
            } else {
                $this->checkoutAction($invoice);
                $check_product_category = $this->product($invoiceid);
                $url = '';
                if ($check_product_category->category == 'product') {
                    $url = 'You can also download the product <a href='.url('download/'.\Auth::user()->id."/$invoice->number").'>here</a>';
                }
                \Cart::clear();

                return redirect()->back()->with('success', \Lang::get('message.check-your-mail-for-further-datails').$url);
            }
        } catch (\Exception $ex) {
            dd($ex);

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
            if ($check_product_category->category == 'product') {
                $url = url("download/$user_id/$invoice_number");
                //execute the order
                $order = new \App\Http\Controllers\Order\OrderController();
                $order->executeOrder($invoice->id, $order_status = 'executed');
            }
            //get system values
            $settings = new Setting();
            $settings = $settings->findOrFail(1);
            $name = \Auth::user()->first_name.' '.\Auth::user()->last_name;
            $from = $settings->email;
            $to = \Auth::user()->email;
            $data = $this->template->where('type', 7)->first()->data;
            $subject = $this->template->where('type', 7)->first()->name;
            $replace = ['url' => $url, 'name' => $name, 'product' => $product];

            //send mail
            $template_controller = new TemplateController();
            $template_controller->Mailing($from, $to, $data, $subject, $replace);
        } catch (\Exception $ex) {
            //dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function product($invoiceid)
    {
        try {
            $invoice = $this->invoiceItem->where('invoice_id', $invoiceid)->first();
            $name = $invoice->product_name;
            $product = $this->product->where('name', $name)->first();

            return $product;
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage());
        }
    }

//    public function GenerateOrder() {
//        try {
//
//            $products = [];
//            $items = \Cart::getContent();
//            foreach ($items as $item) {
//
//                //this is product
//                $id = $item->id;
//                $this->AddProductToOrder($id);
//            }
//        } catch (\Exception $ex) {
//            dd($ex);
//            throw new \Exception('Can not Generate Order');
//        }
//    }
//
//    public function AddProductToOrder($id) {
//        try {
//            $cart = \Cart::get($id);
//            $client = \Auth::user()->id;
//            $payment_method = \Input::get('payment_gateway');
//            $promotion_code = '';
//            $order_status = 'pending';
//            $serial_key = '';
//            $product = $id;
//            $addon = '';
//            $domain = '';
//            $price_override = $cart->getPriceSumWithConditions();
//            $qty = $cart->quantity;
//
//            $planid = $this->price->where('product_id', $id)->first()->subscription;
//
//            $or = $this->order->create(['client' => $client, 'payment_method' => $payment_method, 'promotion_code' => $promotion_code, 'order_status' => $order_status, 'serial_key' => $serial_key, 'product' => $product, 'addon' => $addon, 'domain' => $domain, 'price_override' => $price_override, 'qty' => $qty]);
//
//            $this->AddSubscription($or->id, $planid);
//        } catch (\Exception $ex) {
//            dd($ex);
//            throw new \Exception('Can not Generate Order for Product');
//        }
//    }
//
//    public function AddSubscription($orderid, $planid) {
//        try {
//            $days = $this->plan->where('id', $planid)->first()->days;
//            //dd($days);
//            if ($days > 0) {
//                $dt = \Carbon\Carbon::now();
//                //dd($dt);
//                $user_id = \Auth::user()->id;
//                $ends_at = $dt->addDays($days);
//            } else {
//                $ends_at = '';
//            }
//            $this->subscription->create(['user_id' => \Auth::user()->id, 'plan_id' => $planid, 'order_id' => $orderid, 'ends_at' => $ends_at]);
//        } catch (\Exception $ex) {
//            dd($ex);
//            throw new \Exception('Can not Generate Subscription');
//        }
//    }
//
//    public function GenerateInvoice() {
//        try {
//
//            $user_id = \Auth::user()->id;
//            $number = rand(11111111, 99999999);
//            $date = \Carbon\Carbon::now();
//            $grand_total = \Cart::getSubTotal();
//
//            $invoice = $this->invoice->create(['user_id' => $user_id, 'number' => $number, 'date' => $date, 'grand_total' => $grand_total]);
//            foreach (\Cart::getContent() as $cart) {
//                $this->CreateInvoiceItems($invoice->id, $cart);
//            }
//        } catch (\Exception $ex) {
//            dd($ex);
//            throw new \Exception('Can not Generate Invoice');
//        }
//    }
//
//    public function CreateInvoiceItems($invoiceid, $cart) {
//        try {
//
//            $product_name = $cart->name;
//            $regular_price = $cart->price;
//            $quantity = $cart->quantity;
//            $subtotal = $cart->getPriceSumWithConditions();
//
//            $tax_name = '';
//            $tax_percentage = '';
//
//            foreach ($cart->attributes['tax'] as $tax) {
//                //dd($tax['name']);
//                $tax_name .= $tax['name'] . ',';
//                $tax_percentage .= $tax['rate'] . ',';
//            }
//
//            //dd($tax_name);
//
//            $invoiceItem = $this->invoiceItem->create(['invoice_id' => $invoiceid, 'product_name' => $product_name, 'regular_price' => $regular_price, 'quantity' => $quantity, 'tax_name' => $tax_name, 'tax_percentage' => $tax_percentage, 'subtotal' => $subtotal]);
//        } catch (\Exception $ex) {
//            dd($ex);
//            throw new \Exception('Can not create Invoice Items');
//        }
//    }
}
