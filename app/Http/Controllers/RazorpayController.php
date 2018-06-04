<?php

namespace App\Http\Controllers;

use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
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

    public function payment($invoice, Request $request)
    {
     
        //Input items of form
        $input = Input::all();

        $success = true;
        $error = 'Payment Failed';

        //get API Configuration

        //get API Configuration

        $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) { //Verify Razorpay Payment Id and Signature

            //Fetch payment information by razorpay_payment_id
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id']);
                // $api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay Error : '.$e->getMessage();
            }
        }

        if ($success === true) {
            try {
                //Change order Status as Success if payment is Successful
                $control = new \App\Http\Controllers\Order\RenewController();
                if ($control->checkRenew() == false) {
                    $invoice = Invoice::where('id', $invoice)->first();
                    // $invoicenumber=$invoice->number;
                    // dd($invoice ,$invoicenumber);
                    // $invoiceid = $request->input('orderNo');
                    // dd( $invoiceid);
                    // $invoice = $invoice->findOrFail($invoiceid);

                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();

                    $checkout_controller->checkoutAction($invoice);
                } else {
                    $control->successRenew();
                }
                // $returnValue=$checkout_controller->checkoutAction($invoice);

                \Cart::clear();
                $status = 'success';
                $message = 'Thank you for your order. Your transaction is successful. We will be processing your order soon.';

                return redirect()->back()->with($status, $message);
            } catch (\Exception $ex) {
                dd($ex);
                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }
        }
    }
}
