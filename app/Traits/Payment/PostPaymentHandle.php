<?php

namespace App\Traits\Payment;

use App\Http\Controllers\Tenancy\TenantController;
use App\Model\Common\FaveoCloud;
use App\Model\Common\Setting;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Order\OrderInvoiceRelation;
use App\Model\Order\Payment;
use App\Model\Payment\Currency;
use App\Model\Product\Product;
use App\User;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

//////////////////////////////////////////////////////////////////////////////
// Handle the post manual payment
//////////////////////////////////////////////////////////////////////////////

trait PostPaymentHandle
{
    public static function sendFailedPaymenttoAdmin($invoice, $total, $productName, $exceptionMessage, $user)
    {
        $amount = currencyFormat($total, \Auth::user()->currency);
        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $orderid = OrderInvoiceRelation::where('invoice_id', $invoice->id)->value('order_id');
        $order = Order::find($orderid);
        $setting = Setting::find(1);
        $paymentFailData = 'Payment for'.' '.'of'.' '.\Auth::user()->currency.' '.round($total).' '.'failed by'.' '.\Auth::user()->first_name.' '.\Auth::user()->last_name.' '.'. User Email:'.' '.\Auth::user()->email.'<br>'.'Reason:'.$exceptionMessage;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mail->SendEmail($setting->email, $setting->company_email, $paymentFailData, 'Payment failed ');
        if ($payment) {
            $message = $invoice->is_renewed == 1 ? 'Product renew' : 'Product purchase';
            $mail->payment_log($user->email, $payment->payment_method, $payment->payment_status, $order->number, $exceptionMessage, $amount, $message);
        }
    }

    public static function sendPaymentSuccessMailtoAdmin($invoice, $total, $user, $productName)
    {
        $amount = currencyFormat($total, \Auth::user()->currency);
        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $orderid = OrderInvoiceRelation::where('invoice_id', $invoice->id)->value('order_id');
        $order = Order::find($orderid);
        $setting = Setting::find(1);
        $paymentSuccessdata = 'Payment for '.$productName.' of '.\Auth::user()->currency.' '.round($total).' successful by '.$user->first_name.' '.$user->last_name.' Email: '.$user->email;

        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mail->SendEmail($setting->email, $setting->company_email, $paymentSuccessdata, 'Payment Successful');
        if ($payment) {
            $message = $invoice->is_renewed == 1 ? 'Product renew' : 'Product purchase';
            $mail->payment_log($user->email, $payment->payment_method, $payment->payment_status, $order->number, null, $amount, $message);
        }
    }

    public function processPaymentSuccess($invoice, $currency)
    {
        try {
            $user = User::find($invoice->user_id);
            $stateCode = \Auth::user()->state;
            $cont = new \App\Http\Controllers\RazorpayController();
            $state = $cont->getState($stateCode);
            $currency = Currency::where('code', $currency)->pluck('symbol')->first();

            $control = new \App\Http\Controllers\Order\RenewController();
            $cloud = new \App\Http\Controllers\Tenancy\CloudExtraActivities(new Client, new FaveoCloud());
            // After Regular Payment
            if ($control->checkRenew($invoice->is_renewed) === false && $invoice->is_renewed == 0 && ! $cloud->checkUpgradeDowngrade()) {
                $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                $checkout_controller->checkoutAction($invoice);

                $this->doTheDeed($invoice);

                if (! empty($invoice->cloud_domain)) {
                    $orderNumber = Order::where('invoice_id', $invoice->id)->whereIn('product', cloudPopupProducts())->value('number');
                    (new TenantController(new Client, new FaveoCloud()))->createTenant(new Request(['orderNo' => $orderNumber, 'domain' => $invoice->cloud_domain]));
                }

                $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
            } elseif ($cloud->checkAgentAlteration()) {
                if (\Session::has('agentIncreaseDate')) {
                    $control->successRenew($invoice);
                    \Session::forget('agentIncreaseDate');
                }

                $subId = \Session::get('AgentAlteration');
                $newAgents = \Session::get('newAgents');
                $orderId = \Session::get('orderId');
                $installationPath = \Session::get('installation_path');
                $productId = \Session::get('product_id');
                $oldLicense = \Session::get('oldLicense');
                $payment = new \App\Http\Controllers\Order\InvoiceController();
                $payment->postRazorpayPayment($invoice);
                Invoice::where('id', $invoice->id)->update(['status' => 'success']);
                if ($invoice->grand_total && emailSendingStatus()) {
                    $this->sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                }
                $this->doTheDeed($invoice);
                $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
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
                $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
            } else {
                $control->successRenew($invoice);
                $payment = new \App\Http\Controllers\Order\InvoiceController();
                $payment->postRazorpayPayment($invoice);
                if ($invoice->grand_total && emailSendingStatus()) {
                    $this->sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                }
                $this->doTheDeed($invoice);
                if (\Session::has('AgentAlterationRenew')) {
                    $newAgents = \Session::get('newAgentsRenew');
                    $orderId = \Session::get('orderIdRenew');
                    $installationPath = \Session::get('installation_pathRenew');
                    $productId = \Session::get('product_idRenew');
                    $oldLicense = \Session::get('oldLicenseRenew');
                    $cloud->doTheAgentAltering($newAgents, $oldLicense, $orderId, $installationPath, $productId);
                }
                $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
            }

            return [
                'status' => $view['status'],
                'message' => $view['message'],
            ];
        } catch (\Exception $e) {
            return redirect('checkout')->with('fails', 'Your payment was declined. '.$e->getMessage().'. Please try again or try the other gateway.');
        }
    }

    public function doTheDeed($invoice)
    {
        $amt_to_credit = Payment::where('user_id', \Auth::user()->id)->where('payment_status', 'success')->where('payment_method', 'Credit Balance')->value('amt_to_credit');
        if ($amt_to_credit) {
            $amt_to_credit = (int) $amt_to_credit - (int) $invoice->billing_pay;
            Payment::where('user_id', \Auth::user()->id)->where('payment_method', 'Credit Balance')->where('payment_status', 'success')->update(['amt_to_credit' => $amt_to_credit]);
            User::where('id', \Auth::user()->id)->update(['billing_pay_balance' => 0]);
            $payment_id = \DB::table('payments')->where('user_id', \Auth::user()->id)->where('payment_status', 'success')->where('payment_method', 'Credit Balance')->value('id');
            $formattedValue = currencyFormat($invoice->billing_pay, $invoice->currency, true);
            $messageAdmin = 'The payment balance of '.$formattedValue.' has been utilized or adjusted with this invoice.'.
                ' You can view the details of the invoice '.
                '<a href="'.config('app.url').'/invoices/show?invoiceid='.$invoice->id.'">'.$invoice->number.'</a>.';

            $messageClient = 'The payment balance of '.$formattedValue.' has been utilized or adjusted with this invoice.'.
                ' You can view the details of the invoice '.
                '<a href="'.config('app.url').'/my-invoice/'.$invoice->id.'">'.$invoice->number.'</a>.';

            \DB::table('credit_activity')->insert(['payment_id' => $payment_id, 'text' => $messageAdmin, 'role' => 'admin', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
            \DB::table('credit_activity')->insert(['payment_id' => $payment_id, 'text' => $messageClient, 'role' => 'user', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
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

    public function getViewMessageAfterPayment($invoice, $state, $currency)
    {
        $orders = Order::where('invoice_id', $invoice->id)->get();
        $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
        \Cart::clear();
        $status = 'Success';
        $message = view('themes.default1.front.postPaymentTemplate', compact('invoice', 'orders',
            'invoiceItems', 'state', 'currency'))->render();

        return ['status' => $status, 'message' => $message];
    }

    public function getViewMessageAfterRenew($invoice, $state, $currency)
    {
        $order = OrderInvoiceRelation::where('invoice_id', $invoice->id)->value('order_id');
        $order_number = Order::where('id', $order)->value('number');
        $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)->first();
        $product = Product::where('name', $invoiceItem->product_name)->first();
        $date1 = new DateTime($invoiceItem->created_at);
        $date = $date1->format('M j, Y, g:i a ');
        \Cart::clear();
        $status = 'Success';
        $message = view('themes.default1.front.postRenewTemplate', compact('invoice', 'date',
            'product', 'invoiceItem', 'state', 'currency', 'order_number'))->render();

        return ['status' => $status, 'message' => $message];
    }

    public function calculateUnitCost($currency, $cost)
    {
        $decimalPlaces = [
            'BIF' => 0, 'CLP' => 0, 'DJF' => 0, 'GNF' => 0, 'JPY' => 0,
            'KMF' => 0, 'KRW' => 0, 'MGA' => 0, 'PYG' => 0, 'RWF' => 0,
            'UGX' => 0, 'VND' => 0, 'VUV' => 0, 'XAF' => 0, 'XOF' => 0,
            'XPF' => 0, 'BHD' => 3, 'JOD' => 3, 'KWD' => 3, 'OMR' => 3,
            'TND' => 3,
        ];

        $decimalPlacesForCurrency = $decimalPlaces[$currency] ?? 2;

        if ($decimalPlacesForCurrency === 0) {
            $unit_cost = round((int) $cost);
        } elseif ($decimalPlacesForCurrency === 3) {
            $unit_cost = round((int) $cost) * 1000;
        } else {
            $unit_cost = round((int) $cost) * 100;
        }

        return $unit_cost;
    }
}
