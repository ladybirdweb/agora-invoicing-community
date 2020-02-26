<?php

namespace App\Plugins\Stripe\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Stripe\Model\Stripe;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $stripe = new Stripe();
        $this->stripe = $stripe;
    }

    public function PassToPayment($requests)
    {
        try {
            $request = $requests['request'];
            $order = $requests['order'];
            $cart = $requests['cart'];
            if ($cart->count() > 0) {
                $total = \Cart::getSubTotal();
            } else {
                $total = $request->input('cost');
                \Cart::clear();
                \Session::put('invoiceid', $order->id);
            }

            if ($request->input('payment_gateway') == 'stripe') {
                if (!\Schema::hasTable('stripe')) {
                    throw new \Exception('Paypal is not configured');
                }
                $stripe = $this->stripe->where('id', 1)->first();
                if (!$stripe) {
                    throw new \Exception('Paypal Fields not given');
                }
                $data = $this->getFields($order);
                $this->middlePage($data);
            }
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function getFields($invoice)
    {
        try {
            //dd($invoice);
            $item = [];
            $data = [];
            $user = \Auth::user();
            if (!$user) {
                throw new \Exception('No autherized user');
            }
            $config = $this->stripe->where('id', 1)->first();
            if ($config) {
                $image_url = $config->image_url;
                $currency_code = $invoice->currency;
                $invoice_id = $invoice->id;
                $first_name = $user->first_name;
                $last_name = $user->last_name;
                $address1 = $user->address;
                $city = $user->town;
                $zip = $user->zip;
                $email = $user->email;
                $product_name = '';
                if ($invoice->invoiceItem()->first()) {
                    $product_name = str_replace(' ', '-', $invoice->invoiceItem()->first()->product_name);
                }

                $data = [
                    'image_url'     => $image_url,
                    'currency_code' => 'USD', //$currency_code,
                    'invoice'       => $invoice_id,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'address1'      => $address1,
                    'city'          => $city,
                    'zip'           => $zip,
                    'email'         => $email,
                    'item_name'     => $product_name,
                ];

                $items = $invoice->invoiceItem()->get()->toArray();
                //dd($items);
                $c = count($items);
                if (count($items) > 0) {
                    for ($i = 0; $i < $c; $i++) {
                        $n = $i + 1;
                        $item = [
                            "item_name_$n" => $items[$i]['product_name'],
                            "quantity_$n"  => $items[$i]['quantity'],
                        ];
                    }
                    $data = array_merge($data, $item);
                    $total = ['amount' => $invoice->grand_total];
                    $data = array_merge($data, $total);
                }
            }

            return $data;
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function postCurl($data)
    {
        try {
            $config = $this->paypal->where('id', 1)->first();
            if (!$config) {
                throw new \Exception('Paypal Fields not given');
            }
            $url = $config->paypal_url;
            $post_data = http_build_query($data);
            echo $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }



    public function middlePage($data)
    {
        try {
            $path = app_path().'/Plugins/Stripe/views';
            \View::addNamespace('plugins', $path);
            echo view('plugins::middle-page', compact('data'));
        } catch (\Exception $ex) {
            dd($ex);
        }
    }

    public function response(Request $request)
    {
        $id = '';
        $url = 'checkout';
        if (\Session::has('invoiceid')) {
            $invoiceid = \Session::get('invoiceid');
            $url = 'paynow/'.$id;
        }
        // if (\Cart::getContent()->count() > 0) {
        //     \Cart::clear();
        // }
        if ($invoiceid) {
            $control = new \App\Http\Controllers\Order\RenewController();
            if ($control->checkRenew() === false) {
                $invoice = new \App\Model\Order\Invoice();
                $invoice = $invoice->findOrFail($invoiceid);
                $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                $state = \Auth::user()->state;
                $currency = \Auth::user()->currency_symbol;
                $checkout_controller->checkoutAction($invoice);
                $cont = new \App\Http\Controllers\RazorpayController();
                $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
                $status = $view['status'];
                $message = $view['message'];
                \Session::forget('items');
                \Session::forget('code');
                \Session::forget('codevalue');
            } else {
                $invoice = new \App\Model\Order\Invoice();
                $invoice = $invoice->findOrFail($invoiceid);
                $control->/* @scrutinizer ignore-call */
                successRenew($invoice);
                $payment = new \App\Http\Controllers\Order\InvoiceController();
                $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);
                $state = \Auth::user()->state;
                $currency = \Auth::user()->currency_symbol;
                $cont = new \App\Http\Controllers\RazorpayController();
                $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                $status = $view['status'];
                $message = $view['message'];
            }

            return redirect()->back()->with($status, $message);
            \Cart::clear();
        }
    }



    public function cancel(Request $request)
    {
        $url = 'checkout';
        if (\Session::has('invoiceid')) {
            $id = \Session::get('invoiceid');
            $url = 'paynow/'.$id;
        }
        \Session::forget('invoiceid');

        return redirect($url)->with('fails', 'Thank you for your order. However,the transaction has been declined. Try again.');
    }

    public function notify(Request $request)
    {
        dd($request);
    }
}
