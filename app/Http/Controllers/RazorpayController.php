<?php

namespace App\Http\Controllers;

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
        $invoice = Invoice::where('id', $invoice)->first();
        if ($success === true) {
            try {
                //Change order Status as Success if payment is Successful
                $control = new \App\Http\Controllers\Order\RenewController();

                if ($control->checkRenew() == false) {

                    // $invoicenumber=$invoice->number;
                    // dd($invoice ,$invoicenumber);
                    // $invoiceid = $request->input('orderNo');
                    // dd( $invoiceid);
                    // $invoice = $invoice->findOrFail($invoiceid);

                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();

                    $checkout_controller->checkoutAction($invoice);

                    $order = Order::where('invoice_id', $invoice->id)->first();
                    $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->first();
                    $date1 = new DateTime($order->created_at);
                    $tz = \Auth::user()->timezone()->first()->name;

                    $date1->setTimezone(new DateTimeZone($tz));
                    $date = $date1->format('D ,M j,Y, g:i a ');
                    $product = Product::where('id', $order->product)->select('id', 'name')->first();

                    \Cart::clear();
                    $status = 'success';
                    $message = '



<div class="container">
                            
            
            <div >

            <!-- main content -->
            <div >

                            
    <div id="content" role="main">
                
           <div class="page-content">
                    <div>

    
        
            <strong>Thank you. Your Payment has been received. A confirmation Mail has been sent to you on your registered
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
                
                <li class="woocommerce-order-overview__total total">
                    Total:                    <strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$order->price_override.'</span></strong>
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
        <strong>'.$product->name.' ×   '.$order->qty.' </strong>
    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$invoiceItem->regular_price.'</span>    </td>

</tr>

        </tbody>
        <tfoot>
                                <tr>
                        <th scope="row">Order No:</th>
                        <td><span class="woocommerce-Price-amount amount"> '.$order->number.'</span></td>
                    </tr>
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>
                                        <tr>
                        <th scope="row">Total:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$order->price_override.'</span></td>
                    </tr>
                            </tfoot>
    </table>
    <br>
    
            <section class="woocommerce-customer-details">

    
    <h2 style="margin-bottom:20px;">Billing address</h2>

    <strong>
       '.$firstName.' '.$lastName.'<br>'.$address.'<br>'.$city.' - '.$zip.'<br> '.$state.' <br>
                   '.$phone.' <br><br>
                     <a href= product/download/'.$product->id.' " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
            </strong>

    
</section>
    

</section>

    

</div>
                </div>
           

        
    </div>

        

</div><!-- end main content -->

    
    </div>
    </div>';
                } else {
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);

                    $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->first();
                    $product = Product::where('name', $invoiceItem->product_name)->first();
                    $date1 = new DateTime($invoiceItem->created_at);

                    $tz = \Auth::user()->timezone()->first()->name;

                    $date1->setTimezone(new DateTimeZone($tz));
                    $date = $date1->format('D ,M j,Y, g:i a ');

                    \Cart::clear();
                    $status = 'success';
                    $message = '


<div class="container">
                            
            
            <div >

            <!-- main content -->
            <div >

                            
    <div role="main">
                
            <article>
                
                
                <div class="page-content">
                    <div>
<div>

    
        
            <strong>Thank you. Your Subscription has been renewed.</strong>
                <br>
            <ul>

                <li class="">
                    Invoice Number:                    <strong>'.$invoice->number.'</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date:                    <strong>'.$date.'</strong>
                </li>

                                    <li class="woocommerce-order-overview__email email">
                        Email:                        <strong>'.$email.'</strong>
                    </li>
                
                <li class="woocommerce-order-overview__total total">
                    Total:                    <strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$invoiceItem->subtotal.'</span></strong>
                </li>

                                    <li class="woocommerce-order-overview__payment-method method">
                        Payment method:                        <strong>Razorpay</strong>
                    </li>
                
            </ul>

        
       
<section>
    
    <h2 style="margin-top:40px ; margin-bottom:10px;">Payment Details</h2>
    
    <table class="table table-bordered  table-striped">
    
        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name">Product</th>
                <th class="woocommerce-table__product-table product-total">Total</th>
            </tr>
        </thead>
        
        <tbody>
        <td>
        <strong>'.$product->name.'</strong>×  <strong> '.$order->qty.' </strong>
    </td>
            <tr class="woocommerce-table__line-item order_item">

    <td class="woocommerce-table__product-name product-name">
        <strong>'.$invoiceItem->product_name.'</strong> <strong>× '.$invoiceItem->quantity.'</strong>    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$invoiceItem->regular_price.'</span>    </td>

</tr>

        </tbody>
        <tfoot>
                               
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Razorpay</td>
                    </tr>

                                        <tr>
                        <th scope="row">Total:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.$currency.'</span> '.$invoiceItem->subtotal.'</span></td>
                    </tr>
                            </tfoot>
    </table>
    <br>
    
            <section class="woocommerce-customer-details">

    
    <h2 style="margin-bottom:20px;">Billing address</h2>

    <strong>
       '.$firstName.' '.$lastName.'<br>'.$address.'<br>'.$state.'<br> '.$zip.' <br>
                   '.$phone.' <br><br>
                     <a href=" product/download/'.$product->id.' " class="btn btn-sm btn-primary btn-xs" style="margin-bottom:15px;"><i class="fa fa-download" style="color:white;"> </i>&nbsp;&nbsp;Download the Latest Version here</a>
                   
            </strong>

</section>
    

</section>

    
</div>
</div>
                </div>
            </article>

           

        
    </div>

        

</div><!-- end main content -->

    
    </div>
    </div>';
                }
                // $returnValue=$checkout_controller->checkoutAction($invoice);

                return redirect()->back()->with($status, $message);
            } catch (\Exception $ex) {
                dd($ex);

                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }
        }
    }
}
