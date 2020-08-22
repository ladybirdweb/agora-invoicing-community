<?php

namespace App\Plugins\Stripe\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Stripe\Model\StripePayment;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $stripe = new StripePayment();
        $this->stripe = $stripe;
    }

    public function PassToPayment($requests)
    {
        try {
            $request = $requests['request'];
            $invoice = $requests['invoice'];
            $cart = \Cart::getContent();
            if (! $cart->count()) {
                \Cart::clear();
            } else {
                $invoice->grand_total = \Cart::getTotal();
            }
            if ($request->input('payment_gateway') == 'Stripe') {
                if (! \Schema::hasTable('stripe')) {
                    throw new \Exception('Stripe is not configured');
                }
                $stripe = $this->stripe->where('id', 1)->first();
                if (! $stripe) {
                    throw new \Exception('Stripe Fields not given');
                }
                \Session::put('invoice', $invoice);
                \Session::save();
                $this->middlePage();
            }
        } catch (\Exception $ex) {
            dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function middlePage()
    {
        try {
            $path = app_path().'/Plugins/Stripe/views';
            $total = \Cart::getTotal();
            if (! $total) {
                $total = \Session::get('totalToBePaid');
            }
            \View::addNamespace('plugins', $path);
            echo view('plugins::middle-page', compact('total'));
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
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
