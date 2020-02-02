<?php

namespace App\Http\Controllers\Front;

use App\ApiKey;
use App\Http\Controllers\Common\MailChimpController;
use App\Http\Controllers\Common\TemplateController;
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
use Bugsnag;
use Cart;
use Illuminate\Http\Request;

class CheckoutController extends InfoController
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

    /*
      * When Proceed to chekout button clicked first request comes here
     */
    public function checkoutForm(Request $request)
    {
        if (!\Auth::user()) {//If User is not Logged in then send him to login Page
            $url = $request->segments(); //The requested url (chekout).Save it in Session
            \Session::put('session-url', $url[0]);
            $content = Cart::getContent();
            $domain = $request->input('domain');
            if ($domain) {
                foreach ($domain as $key => $value) {
                    \Session::put('domain'.$key, $value); //Store all the domains Entered in Cart Page in Session
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

            $content = Cart::getContent();
        }

        try {
            $domain = $request->input('domain');
            if ($domain) {//Store the Domain  in session when user Logged In
                foreach ($domain as $key => $value) {
                    \Session::put('domain'.$key, $value);
                }
            }

            return view('themes.default1.front.checkout', compact('content', 'attributes'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get all the Attributes Sent From the cart along with Tax Conditions.
     *
     * @param array $content Collection of the Cart Values
     *
     * @return array Items along with their details,Attributes(Currency,Agents) and Tax Conditions
     */
    public function getAttributes($content)
    {
        try {
            if (count($content) > 0) {//after ProductPurchase this is not true as cart is cleared
                foreach ($content as $key => $item) {
                    $attributes[] = $item->attributes;
                    $cart_currency = $attributes[0]['currency']['currency']; //Get the currency of Product in the cart
                    $currency = \Auth::user()->currency != $cart_currency ? \Auth::user()->currency : $cart_currency; //If User Currency and cart currency are different the currency es set to user currency.
                    if ($cart_currency != $currency) {
                        $id = $item->id;
                        Cart::remove($id);
                    }
                    $require_domain = $this->product->where('id', $item->id)->first()->require_domain;
                    $require = [];
                    if ($require_domain == 1) {
                        $require[$key] = $item->id;
                    }
                    $cont = new CartController();
                    $taxConditions = $cont->checkTax($item->id); //Calculate Tax Condition by passing ProductId
                    Cart::remove($item->id);

                    //Return array of Product Details,attributes and their conditions
                    $items[] = ['id' => $item->id, 'name' => $item->name, 'price' => $item->price,
                    'quantity'       => $item->quantity, 'attributes' => ['currency'=> $attributes[0]['currency'],
                    'agents'                                                        => $attributes[0]['agents'], 'tax'=>$taxConditions['tax_attributes'], ], 'conditions'=>$taxConditions['conditions'], ];
                }
                Cart::add($items);
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function payNow($invoiceid)
    {
        try {
            $invoice = $this->invoice->find($invoiceid);
            $items = new \Illuminate\Support\Collection();
            // dd($items);
            if ($invoice) {
                $items = $invoice->invoiceItem()->get();
                if (count($items) > 0) {
                    $product = $this->product($invoiceid);
                }
            }

            return view('themes.default1.front.paynow', compact('invoice', 'items', 'product'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postCheckout(Request $request)
    {
        $invoice_controller = new \App\Http\Controllers\Order\InvoiceController();
        $info_cont = new \App\Http\Controllers\Front\InfoController();
        $payment_method = $request->input('payment_gateway');
        \Session::put('payment_method', $payment_method);
        $paynow = $this->checkregularPaymentOrRenewal($request->input('invoice_id'));
        $cost = $request->input('cost');
        $state = $this->getState();
        if (Cart::getSubTotal() != 0 || $cost > 0) {
            $this->validate($request, [
                    'payment_gateway'=> 'required',
                    ], [
                        'payment_gateway.required'=> 'Please Select a Payment Gateway',
                    ]);
        }

        try {
            if ($paynow === false) {
                /*
                 * Do order, invoicing etc
                 */

                $invoice = $invoice_controller->generateInvoice();

                $pay = $this->payment($payment_method, $status = 'pending');
                $payment_method = $pay['payment'];
                $status = $pay['status'];
                $invoice_no = $invoice->number;
                $date = $this->getDate($invoice);
                $invoiceid = $invoice->id;
                $amount = $invoice->grand_total;
                $url = '';
                $cart = Cart::getContent();
                $invoices = $this->invoice->find($invoiceid);
                $items = new \Illuminate\Support\Collection();
                if ($invoices) {
                    $items = $invoice->invoiceItem()->get();
                    $product = $this->product($invoiceid);
                    $content = Cart::getContent();
                    $attributes = $this->getAttributes($content);
                }
            } else {
                $items = new \Illuminate\Support\Collection();
                $invoiceid = $request->input('invoice_id');
                $invoice = $this->invoice->find($invoiceid);
                $invoice_no = $invoice->number;
                $date = $this->getDate($invoice);
                $items = $invoice->invoiceItem()->get();
                $product = $this->product($invoiceid);
                $amount = $invoice->grand_total;
                $content = Cart::getContent();
                $attributes = $this->getAttributes($content);
            }
            if (Cart::getSubTotal() != 0 || $cost > 0) {
                if ($payment_method == 'razorpay') {
                    $rzp_key = ApiKey::where('id', 1)->value('rzp_key');
                    $rzp_secret = ApiKey::where('id', 1)->value('rzp_secret');
                    $apilayer_key = ApiKey::where('id', 1)->value('apilayer_key');

                    return view(
                        'themes.default1.front.postCheckout',
                        compact(
                            'amount',
                            'invoice_no',
                            'invoiceid',
                            'payment_method',
                            'invoice',
                            'items',
                            'product',
                            'paynow',
                            'content',
                            'attributes',
                            'rzp_key',
                            'rzp_secret',
                            'apilayer_key'
                        )
                    );
                } else {
                    \Event::dispatch(new \App\Events\PaymentGateway(['request' => $request, 'cart' => Cart::getContent(), 'order' => $invoice]));
                }
            } else {
                if ($paynow == false) {//Regular Payment for free Product
                    $action = $this->checkoutAction($invoice);
                } else {//Renewal Payment for free Product
                    $control = new \App\Http\Controllers\Order\RenewController();
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);
                }

                // $check_product_category = $this->product($invoiceid);
                $url = '';
                // if ($check_product_category->category) {
                $url = view('themes.default1.front.postCheckoutTemplate', compact(
                        'invoice',
                        'date',
                        'product',
                        'items',
                        'attributes',
                        'state'
                    ))->render();
                // }

                \Cart::clear();

                return redirect()->back()->with('success', $url);
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkregularPaymentOrRenewal($invoiceid)
    {
        $paynow = false;
        if ($invoiceid) {
            $paynow = true;
        }

        return $paynow;
    }

    public function checkoutAction($invoice)
    {
        try {
            //get elements from invoice
            $invoice_number = $invoice->number;
            $invoice_id = $invoice->id;
            $invoice->status = 'success';
            $invoice->save();
            $user_id = \Auth::user()->id;

            $url = '';

            $url = url("download/$user_id/$invoice->number");
            $payment = new \App\Http\Controllers\Order\InvoiceController();
            $payment->postRazorpayPayment($invoice_id, $invoice->grand_total);
            //execute the order
            $order = new \App\Http\Controllers\Order\OrderController();
            $order->executeOrder($invoice->id, $order_status = 'executed');

            return 'success';
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

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
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }
}
