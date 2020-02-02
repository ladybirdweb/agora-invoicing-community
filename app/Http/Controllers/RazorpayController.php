<?php

namespace App\Http\Controllers;

use App\ApiKey;
use App\Model\Common\State;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\TaxByState;
use App\Model\Product\Product;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Razorpay\Api\Api;
use Redirect;

class RazorpayController extends Controller
{
    public $invoice;
    public $invoiceItem;

    public function __construct()
    {
        $invoice = new Invoice();
        $this->invoice = $invoice;

        $invoiceItem = new InvoiceItem();
        $this->invoiceItem = $invoiceItem;

        // $mailchimp = new MailChimpController();
        // $this->mailchimp = $mailchimp;
    }

    public function payWithRazorpay()
    {
        $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));

        return view('themes.default1.front.checkout', compact('api'));
    }

    /*
    * Create Order And Payment for invoice paid with Razorpay
     */
    public function payment($invoice, Request $request)
    {
        //Input items of form
        $input = $request->all();
        $success = true;
        $error = 'Payment Failed';
        $rzp_key = ApiKey::where('id', 1)->value('rzp_key');
        $rzp_secret = ApiKey::where('id', 1)->value('rzp_secret');

        $api = new Api($rzp_key, $rzp_secret);
        dd($api->payment->fetch($input['razorpay_payment_id']));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) { //Verify Razorpay Payment Id and Signature

            //Fetch payment information by razorpay_payment_id
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id']);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : '.$e->getMessage();
            }
        }
        $stateCode = \Auth::user()->state;
        $state = $this->getState($stateCode);
        $currency = $this->getCurrency();
        $invoice = Invoice::where('id', $invoice)->first();

        if ($success === true) {
            try {
                //Change order Status as Success if payment is Successful
                $control = new \App\Http\Controllers\Order\RenewController();
                //After Regular Payment
                if ($control->checkRenew() === false) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);

                    $view = $this->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                    \Session::forget('items');
                    \Session::forget('code');
                    \Session::forget('codevalue');
                } else {
                    //Afer Renew
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);
                    $view = $this->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                }

                return redirect()->back()->with($status, $message);
            } catch (\Exception $ex) {
                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }
        }
    }

    public function getCurrency()
    {
        $symbol = \Auth::user()->currency_symbol;

        return $symbol;
    }

    public function getState($stateCode)
    {
        if (\Auth::user()->country != 'IN') {
            $state = State::where('state_subdivision_code', $stateCode)->pluck('state_subdivision_name')->first();
        } else {
            $state = TaxByState::where('state_code', \Auth::user()->state)->pluck('state')->first();
        }

        return $state;
    }

    public function getViewMessageAfterPayment($invoice, $state, $currency)
    {
        $orders = Order::where('invoice_id', $invoice->id)->get();
        $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();

        \Cart::clear();
        $status = 'success';
        $message = view('themes.default1.front.postPaymentTemplate', compact('invoice','orders',
             'invoiceItems', 'state', 'currency'))->render();

        return ['status'=>$status, 'message'=>$message];
    }

    public function getViewMessageAfterRenew($invoice, $state, $currency)
    {
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->first();
        $product = Product::where('name', $invoiceItem->product_name)->first();
        $date1 = new DateTime($invoiceItem->created_at);

        $tz = \Auth::user()->timezone()->first()->name;

        $date1->setTimezone(new DateTimeZone($tz));
        $date = $date1->format('M j, Y, g:i a ');

        \Cart::clear();
        $status = 'success';

        $message = view('themes.default1.front.postRenewTemplate', compact('invoice','date',
            'product', 'invoiceItem', 'state', 'currency'))->render();

        return ['status'=>$status, 'message'=>$message];
    }
}
