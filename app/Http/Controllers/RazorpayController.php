<?php

namespace App\Http\Controllers;

use App\ApiKey;
use App\Model\Common\FaveoCloud;
use App\Model\Common\State;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Order\Payment;
use App\Model\Payment\TaxByState;
use App\Model\Product\Product;
use App\Plugins\Stripe\Controllers\SettingsController;
use App\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Razorpay\Api\Api;

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

    /*
    * Create Order And Payment for invoice paid with Razorpay
     */
    public function payment($invoice, Request $request)
    {
        $userId = Invoice::find($invoice)->user_id;
        if (\Auth::user()->role != 'admin' && $userId != \Auth::user()->id) {
            return errorResponse('Payment cannot be initiated. Invalid modification of data');
        }
        //Input items of form
        $input = $request->all();
        $error = 'Payment Failed';
        $rzp_key = ApiKey::where('id', 1)->value('rzp_key');
        $rzp_secret = ApiKey::where('id', 1)->value('rzp_secret');
        $invoice = Invoice::where('id', $invoice)->first();
        if (count($input) && ! empty($input['razorpay_payment_id'])) { //Verify Razorpay Payment Id and Signature
            //Fetch payment information by razorpay_payment_id
            try {
                $api = new Api($rzp_key, $rzp_secret);
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $response = $api->payment->fetch($input['razorpay_payment_id']);

                $stateCode = \Auth::user()->state;
                $state = $this->getState($stateCode);
                $currency = $this->getCurrency();

                //Change order Status as Success if payment is Successful
                $control = new \App\Http\Controllers\Order\RenewController();
                $cloud = new \App\Http\Controllers\Tenancy\CloudExtraActivities(new Client, new FaveoCloud());

                //After Regular Payment
                if ($control->checkRenew() === false && $invoice->is_renewed == 0) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);
                    $view = $this->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                    \Session::forget('items');
                    \Session::forget('code');
                    \Session::forget('codevalue');
                    $this->doTheDeed($invoice);
                } elseif ($cloud->checkAgentAlteration()) {
                    $subId = \Session::get('AgentAlteration'); // use if needed in future
                    $newAgents = \Session::get('newAgents');
                    $orderId = \Session::get('orderId');
                    $installationPath = \Session::get('installation_path');
                    $productId = \Session::get('product_id');
                    $oldLicense = \Session::get('oldLicense');
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    Invoice::where('id', $invoice->id)->update(['status'=> 'success']);
                    if ($invoice->grand_total) {
                        SettingsController::sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, \Auth::user(), $invoice->invoiceItem()->first()->product_name);
                    }
                    $view = $this->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                    $this->doTheDeed($invoice);
                    $cloud->doTheAgentAltering($newAgents, $oldLicense, $orderId, $installationPath, $productId);
                } elseif ($cloud->checkUpgradeDowngrade()) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);
                    $oldLicense = \Session::get('upgradeOldLicense');
                    $installationPath = \Session::get('upgradeInstallationPath');
                    $productId = \Session::get('upgradeProductId');
                    $licenseCode = \Session::get('upgradeSerialKey');
                    $this->doTheDeed($invoice);
                    $cloud->doTheProductUpgradeDowngrade($licenseCode, $installationPath, $productId, $oldLicense);
                    $view = $this->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                } else {
                    //Afer Renew
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    if ($invoice->grand_total) {
                        SettingsController::sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, \Auth::user(), $invoice->invoiceItem()->first()->product_name);
                    }
                    $this->doTheDeed($invoice);

                    $view = $this->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                }
                \Cart::removeCartCondition('Processing fee');

                return redirect('checkout')->with($status, $message);
            } catch (\Razorpay\Api\Errors\SignatureVerificationError|\Razorpay\Api\Errors\BadRequestError|\Razorpay\Api\Errors\GatewayError|\Razorpay\Api\Errors\ServerError $e) {
                SettingsController::sendFailedPaymenttoAdmin($invoice, $invoice->grand_total, $invoice->invoiceItem()->first()->product_name, $e->getMessage(), \Auth::user());

                return redirect('checkout')->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
            } catch (\Exception $e) {
                return redirect('checkout')->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
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
        $message = view('themes.default1.front.postPaymentTemplate', compact('invoice', 'orders',
            'invoiceItems', 'state', 'currency'))->render();

        return ['status' => $status, 'message' => $message];
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

        $message = view('themes.default1.front.postRenewTemplate', compact('invoice', 'date',
            'product', 'invoiceItem', 'state', 'currency'))->render();

        return ['status' => $status, 'message' => $message];
    }

    private function doTheDeed($invoice)
    {
        $amt_to_credit = Payment::where('user_id', \Auth::user()->id)->where('payment_status', 'success')->where('payment_method', 'Credit Balance')->value('amt_to_credit');
        if ($amt_to_credit) {
            $amt_to_credit = $amt_to_credit - $invoice->billing_pay;
            Payment::where('user_id', \Auth::user()->id)->where('payment_method', 'Credit Balance')->where('payment_status', 'success')->update(['amt_to_credit'=>$amt_to_credit]);
            User::where('id', \Auth::user()->id)->update(['billing_pay_balance'=>0]);
            $payment_id = \DB::table('payments')->where('user_id', \Auth::user()->id)->where('payment_status', 'success')->where('payment_method', 'Credit Balance')->value('id');
            $formattedValue = currencyFormat($invoice->billing_pay, $invoice->currency, true);
            $messageAdmin = 'The payment balance of '.$formattedValue.' has been utilized or adjusted with this invoice.'.
                ' You can view the details of the invoice '.
                '<a href="'.config('app.url').'/invoices/show?invoiceid='.$invoice->id.'">'.$invoice->number.'</a>.';

            $messageClient = 'The payment balance of '.$formattedValue.' has been utilized or adjusted with this invoice.'.
                ' You can view the details of the invoice '.
                '<a href="'.config('app.url').'/my-invoice/'.$invoice->id.'">'.$invoice->number.'</a>.';

            \DB::table('credit_activity')->insert(['payment_id'=>$payment_id, 'text'=>$messageAdmin, 'role'=>'admin', 'created_at'=>\Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
            \DB::table('credit_activity')->insert(['payment_id'=>$payment_id, 'text'=>$messageClient, 'role'=>'user', 'created_at'=>\Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
            if ($invoice->billing_pay) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'amount' => $invoice->billing_pay,
                    'payment_method' => 'Credits',
                    'payment_status' => 'success',
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }
}
