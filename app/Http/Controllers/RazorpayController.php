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
                $invoice = Invoice::where('id', $invoice)->first();
                if ($control->checkRenew() == false) {

                    // $invoicenumber=$invoice->number;
                    // dd($invoice ,$invoicenumber);
                    // $invoiceid = $request->input('orderNo');
                    // dd( $invoiceid);
                    // $invoice = $invoice->findOrFail($invoiceid);

                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();

                    $checkout_controller->checkoutAction($invoice);
                } else {
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);
                }
                // $returnValue=$checkout_controller->checkoutAction($invoice);

                \Cart::clear();
                $status = 'success';
                $message = '


<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="stylesheet" href="{{asset("vendor/bootstrap/css/bootstrap.min.css")}}">
   <link href="jumbotron-narrow.css" rel="stylesheet">

   <style>
    <link href="jumbotron-narrow.css" rel="stylesheet">

.panel-title {display: inline;font-weight: bold;}
.checkbox.pull-right { margin: 0; }
.pl-ziro { padding-left: 0px; }

.panel {
    border: 0px solid transparent;
    background: #f1f1f1;
}



.container {

    border-radius:10px;
    margin-top:20px;
    margin-bottom:20px;
}
.container-narrow > hr {
  margin: 30px 0;
      background:#ffffff;
}

.container-narrow > hr {
  margin: 30px 0;
      background:#ffffff;
}

.panel-heading {
    border-bottom: 0px solid #555555 !important;
}

.panel-default>.panel-heading {
    color: #ffffff;
    background-color: #428bca;
    padding-bottom: 1px !important;
}
.header h3 {
  margin-top: 0;
  margin-bottom: 0;
  line-height: 40px;
}

.table {
    margin-bottom: 0px;
}

.invoice-title h2, .invoice-title h3 {
    display: inline-block;
}

.table > tbody > tr > .no-line {
    border-top: none;
}

.table > thead > tr > .no-line {
    border-bottom: none;
}

.table > tbody > tr > .thick-line {
    border-top: 2px solid;
}
   </style>



  </head>

   <body class="main">

    <div class="container">

      <div class="row marketing">
      
        <div class="col-lg-12">
        
          <h4><b>Helpdesk Advance Payment</b></h4>
<hr />

<div>
<center>  
<h4>Success - Your Payment is confirmed!</h4>
<h5>Order number: #243735374</h5>
<hr />  
</div>
</center>
        </div>

          <div class="col-lg-3">
            <address>
                    <strong>User Details:</strong><br>
                        Aniel Simmons<br>
                        name@site.com<br>
                        0737632706<br>
                        Indiranagar<br>
                        560038, Bangalore, India
                    </address>
        </div>
        <div class="col-lg-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center><p style="color:#6FA;padding-left:10px;padding:right:10px;"><span class="glyphicon glyphicon glyphicon-question-sign" aria-hidden="true"></span> 
                    We have mailed you Payment Confirmation Mail and a link to download the product.</p> </center>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Product Name</strong></td>
                                    <td class="text-right"><strong>Invoice Number</strong></td>
                                    <td class="text-right"><strong>Subscription Ends</strong></td>
                                    <td class="text-right"><strong>Price</strong></td>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td>Helpdesk Advance</td>
                                    <td class="text-right">#12323443</td>
                                    <td class="text-right">4-4-2019</td>
                                    <td class="text-right">189234</td>
                                </tr>
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-right"><strong>GST - 12%</strong></td>
                                    <td class="thick-line text-right">incl.</td>
                                </tr>
                               
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-right"><strong>Total</strong></td>
                                    <td class="no-line text-right"> USD 189 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
         </div>
      
   


      </div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
   
  </body>
</html>';

                return redirect()->back()->with($status, $message);
            } catch (\Exception $ex) {
                dd($ex);

                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }
        }
    }
}
