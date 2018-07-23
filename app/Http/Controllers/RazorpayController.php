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


<div class="container">
                            
            
            <div class="row main-content-wrap">

            <!-- main content -->
            <div class="main-content col-lg-9">

                            
    <div id="content" role="main">
                
            <article class="post-23 page type-page status-publish hentry">
                
                <span class="entry-title" style="display: none;">Order received</span><span class="vcard" style="display: none;"><span class="fn"><a href="http://alok-ladybirdweb.tk/sm/author/admin/" title="Posts by admin" rel="author">admin</a></span></span><span class="updated" style="display:none">2018-06-08T11:13:38+00:00</span>
                <div class="page-content">
                    <div class="woocommerce">
<div class="woocommerce-order">

    
        
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">Thank you. Your order has been received.</p>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    Order number:                    <strong>2288</strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    Date:                    <strong>June 8, 2018</strong>
                </li>

                                    <li class="woocommerce-order-overview__email email">
                        Email:                        <strong>admin@ladybirdweb.com</strong>
                    </li>
                
                <li class="woocommerce-order-overview__total total">
                    Total:                    <strong><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>100.00</span></strong>
                </li>

                                    <li class="woocommerce-order-overview__payment-method method">
                        Payment method:                        <strong>Cash on delivery</strong>
                    </li>
                
            </ul>

        
        <p>Pay with cash upon delivery.</p>
        

<section class="woocommerce-order-details">
    
    <h2 class="woocommerce-order-details__title">Order Details</h2>
    
    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
    
        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name">Product</th>
                <th class="woocommerce-table__product-table product-total">Total</th>
            </tr>
        </thead>
        
        <tbody>
            <tr class="woocommerce-table__line-item order_item">

    <td class="woocommerce-table__product-name product-name">
        <a href="http://alok-ladybirdweb.tk/sm/product/test-asu/">test asu</a> <strong class="product-quantity">× 1</strong>    </td>

    <td class="woocommerce-table__product-total product-total">
        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>100.00</span>    </td>

</tr>

        </tbody>
        <tfoot>
                                <tr>
                        <th scope="row">Subtotal:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>100.00</span></td>
                    </tr>
                                        <tr>
                        <th scope="row">Payment method:</th>
                        <td>Cash on delivery</td>
                    </tr>
                                        <tr>
                        <th scope="row">Total:</th>
                        <td><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">₹</span>100.00</span></td>
                    </tr>
                            </tfoot>
    </table>
    <br>
    
            <section class="woocommerce-customer-details">

    
    <h2 class="woocommerce-column__title">Billing address</h2>

    <address>
        Alok jena<br>68 , 10th main Indiranagar<br>Bangalore - 560038<br>Karnataka
                    <p class="woocommerce-customer-details--phone">7795792760</p>
        
                    <p class="woocommerce-customer-details--email">admin@ladybirdweb.com</p>
            </address>

    
</section>
    

</section>

    
</div>
</div>
                </div>
            </article>

            <div class="">
            
                        </div>

        
    </div>

        

</div><!-- end main content -->

    
    </div>
    </div>';

                return redirect()->back()->with($status, $message);
            } catch (\Exception $ex) {
                dd($ex);

                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }
        }
    }
}
