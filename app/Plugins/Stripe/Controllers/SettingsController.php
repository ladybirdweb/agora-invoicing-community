<?php

namespace App\Plugins\Stripe\Controllers;

use App\ApiKey;
use App\Auto_renewal;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncBillingToLatestVersion;
use App\Model\Common\FaveoCloud;
use App\Model\Common\Setting;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Order\OrderInvoiceRelation;
use App\Model\Order\Payment;
use App\Model\Payment\Currency;
use App\Plugins\Stripe\Model\StripePayment;
use App\User;
use Carbon\Carbon;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Schema;
use Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['postPaymentWithStripe']]);
    }

    public function Settings()
    {
        try {
            if (! Schema::hasTable('stripe')) {
                Schema::create('stripe', function ($table) {
                    $table->increments('id');
                    $table->string('image_url');
                    $table->string('processing_fee');
                    $table->string('base_currency');
                    $table->string('currencies');
                    $table->timestamps();
                });
            }

            $stripe1 = new StripePayment();
            // //dd($ccavanue);
            $stripe = $stripe1->where('id', '1')->first();

            if (! $stripe) {
                (new SyncBillingToLatestVersion)->sync();
            }
            $allCurrencies = StripePayment::pluck('currencies', 'id')->toArray();
            $apikey = new ApiKey();
            $stripeKeys = $apikey->select('stripe_key', 'stripe_secret')->first();
            $baseCurrency = StripePayment::pluck('base_currency')->toArray();
            $path = app_path().'/Plugins/Stripe/views';
            \View::addNamespace('plugins', $path);

            return view('plugins::settings', compact('stripe', 'baseCurrency', 'allCurrencies', 'stripeKeys'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postSettings(Request $request)
    {
        $this->validate($request, [
            'business' => 'required',
            'cmd' => 'required',
            'paypal_url' => 'required|url',
            'success_url' => 'url',
            'cancel_url' => 'url',
            'notify_url' => 'url',
            'currencies' => 'required',
        ]);

        try {
            $ccavanue1 = new Paypal();
            $ccavanue = $ccavanue1->where('id', '1')->first();
            $ccavanue->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function changeBaseCurrency(Request $request)
    {
        $baseCurrency = Stripe::where('id', $request->input('b_currency'))->pluck('currencies')->first();
        $allCurrencies = Stripe::select('base_currency', 'id')->get();
        foreach ($allCurrencies as $currencies) {
            Stripe::where('id', $currencies->id)->update(['base_currency' => $baseCurrency]);
        }

        return ['message' => 'success', 'update' => 'Base Currency Updated'];
    }

    public function updateApiKey(Request $request)
    {
        try {
            $stripe = Stripe::make($request->input('stripe_secret'));
            $response = $stripe->customers()->create(['description' => 'Test Customer to Validate Secret Key']);
            $stripe_secret = $request->input('stripe_secret');
            ApiKey::find(1)->update(['stripe_secret' => $stripe_secret]);

            return successResponse(['success' => 'true', 'message' => 'Secret key updated successfully']);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException  $e) {
            return errorResponse($e->getMessage());
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function postPaymentWithStripe(Request $request)
    {
        $validator = Validator::make($request->all(), [
        ]);
        $input = $request->all();
        $validation = [
            'card_no' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvv' => 'required',
        ];

        $this->validate($request, $validation);

        try {
            $invoice = \Session::get('invoice');
            // $invoiceTotal = \Session::get('totalToBePaid');
            $amount = rounding(\Cart::getTotal());
            if (! $amount) {//During renewal
                $amount = rounding(\Session::get('totalToBePaid'));
            }
            $currency = strtolower($invoice->currency);

            $strCharge = $this->stripePay($request);
            if ($strCharge['charge']['status'] == 'succeeded') {
                $stripeCustomerId = $strCharge['customer']['id'];
                $customer_details = [
                    'user_id' => $invoice->user_id,
                    'invoice_number' => $invoice->number,
                    'customer_id' => $stripeCustomerId,
                ];
                Auto_renewal::create($customer_details);
                $user = User::find($invoice->user_id);

                //Change order Status as Success if payment is Successful
                $stateCode = \Auth::user()->state;
                $cont = new \App\Http\Controllers\RazorpayController();
                $state = $cont->getState($stateCode);
                $currency = Currency::where('code', $currency)->pluck('symbol')->first();

                $control = new \App\Http\Controllers\Order\RenewController();
                $cloud = new \App\Http\Controllers\Tenancy\CloudExtraActivities(new Client, new FaveoCloud());
                //After Regular Payment
                if ($control->checkRenew() === false && $invoice->is_renewed == 0 && ! $cloud->checkUpgradeDowngrade()) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);

                    $this->doTheDeed($invoice);

                    $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                } elseif ($cloud->checkAgentAlteration()) {
                    if (\Session::has('agentIncreaseDate')) {
                        $control->successRenew($invoice);
                        \Session::forget('agentIncreaseDate');
                    }

                    $subId = \Session::get('AgentAlteration'); // use if needed in future
                    $newAgents = \Session::get('newAgents');
                    $orderId = \Session::get('orderId');
                    $installationPath = \Session::get('installation_path');
                    $productId = \Session::get('product_id');
                    $oldLicense = \Session::get('oldLicense');
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    Invoice::where('id', $invoice->id)->update(['status'=> 'success']);
                    if ($invoice->grand_total && emailSendingStatus()) {
                        $this->sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                    }
                    $this->doTheDeed($invoice, false);
                    $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
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
                    $status = $view['status'];
                    $message = $view['message'];
                } else {
                    //Afer Renew
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    if ($invoice->grand_total && emailSendingStatus()) {
                        $this->sendPaymentSuccessMailtoAdmin($invoice, $invoice->grand_total, $user, $invoice->invoiceItem()->first()->product_name);
                    }
                    $this->doTheDeed($invoice, false);
                    if (\Session::has('AgentAlterationRenew')) {
                        $newAgents = \Session::get('newAgentsRenew');
                        $orderId = \Session::get('orderIdRenew');
                        $installationPath = \Session::get('installation_pathRenew');
                        $productId = \Session::get('product_idRenew');
                        $oldLicense = \Session::get('oldLicenseRenew');
                        $cloud->doTheAgentAltering($newAgents, $oldLicense, $orderId, $installationPath, $productId);
                    }
                    $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                }
                \Session::forget('items');
                \Session::forget('code');
                \Session::forget('codevalue');
                \Session::forget('totalToBePaid');
                \Session::forget('invoice');
                \Session::forget('cart_currency');
                \Cart::removeCartCondition('Processing fee');

                return redirect('checkout')->with($status, $message);
            } else {
                return redirect('checkout')->with('fails', 'Your Payment was declined. Please try making payment with other gateway');
            }
        } catch (\Cartalyst\Stripe\Exception\ApiLimitExceededException|\Cartalyst\Stripe\Exception\BadRequestException|\Cartalyst\Stripe\Exception\MissingParameterException|\Cartalyst\Stripe\Exception\NotFoundException|\Cartalyst\Stripe\Exception\ServerErrorException|\Cartalyst\Stripe\Exception\StripeException|\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            if (emailSendingStatus()) {
                $this->sendFailedPaymenttoAdmin($invoice, $invoice->grand_total, $invoice->invoiceItem()->first()->product_name, $e->getMessage(), $user);
            }

            return redirect('checkout')->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            if (emailSendingStatus()) {
                $this->sendFailedPaymenttoAdmin($invoice, $invoice->grand_total, $invoice->invoiceItem()->first()->product_name, $e->getMessage(), $user);
            }
            \Session::put('amount', $amount);
            \Session::put('error', $e->getMessage());

            return redirect()->route('checkout');
        } catch (\Exception $e) {
            return redirect('checkout')->with('fails', 'Your payment was declined. '.$e->getMessage().'. Please try again or try the other gateway.');
        }
    }

    public function stripePay($request)
    {
        try {
            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            $stripe = Stripe::make($stripeSecretKey);

            if (\Session::get('invoice')) {
                $invoice = \Session::get('invoice');
                // $invoiceTotal = \Session::get('totalToBePaid');
                $amount = rounding(\Cart::getTotal());
                if (! $amount) {//During renewal
                    $amount = rounding(\Session::get('totalToBePaid'));
                }
                $currency = strtolower($invoice->currency);
            } else {
                $amount = 1;
                $currency = \Auth::user()->currency;
            }

            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('exp_month'),
                    'exp_year' => $request->get('exp_year'),
                    'cvc' => $request->get('cvv'),
                ],
            ]);

            if (! isset($token['id'])) {
                \Session::put('error', 'The Stripe Token was not generated correctly');

                return redirect()->route('stripform');
            }
            $customer = $stripe->customers()->create([
                'name' => \Auth::user()->first_name.' '.\Auth::user()->last_name,
                'email' => \Auth::user()->email,
                'address' => [
                    'line1' => \Auth::user()->address,
                    'postal_code' => \Auth::user()->zip,
                    'city' => \Auth::user()->town,
                    'state' => \Auth::user()->state,
                    'country' => \Auth::user()->country,
                ],
            ]);

            $stripeCustomerId = $customer['id'];
            $card = $stripe->cards()->create($stripeCustomerId, $token['id']);
            $charge = $stripe->charges()->create([
                'customer' => $customer['id'],
                'currency' => $currency,
                'amount' => $amount,
                'description' => 'Add in wallet',
            ]);

            return ['charge' => $charge, 'customer' => $customer];
        } catch(\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public static function sendFailedPaymenttoAdmin($invoice, $total, $productName, $exceptionMessage, $user)
    {
        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $orderid = OrderInvoiceRelation::where('invoice_id', $invoice->id)->value('order_id');
        $order = Order::find($orderid);
        $setting = Setting::find(1);
        $paymentFailData = 'Payment for'.' '.'of'.' '.\Auth::user()->currency.' '.$total.' '.'failed by'.' '.\Auth::user()->first_name.' '.\Auth::user()->last_name.' '.'. User Email:'.' '.\Auth::user()->email.'<br>'.'Reason:'.$exceptionMessage;
        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mail->SendEmail($setting->email, $setting->company_email, $paymentFailData, 'Payment failed ');
        if ($payment) {
            $mail->payment_log($user->email, $payment->payment_method, $payment->payment_status, $order->number, $exceptionMessage);
        }
    }

    public static function sendPaymentSuccessMailtoAdmin($invoice, $total, $user, $productName)
    {
        $payment = Payment::where('invoice_id', $invoice->id)->first();
        $orderid = OrderInvoiceRelation::where('invoice_id', $invoice->id)->value('order_id');
        $order = Order::find($orderid);
        $setting = Setting::find(1);
        $paymentSuccessdata = 'Payment for '.$productName.' of '.\Auth::user()->currency.' '.$total.' successful by '.$user->first_name.' '.$user->last_name.' Email: '.$user->email;

        $mail = new \App\Http\Controllers\Common\PhpMailController();
        $mail->SendEmail($setting->email, $setting->company_email, $paymentSuccessdata, 'Payment Successful ');
        if ($payment) {
            $mail->payment_log($user->email, $payment->payment_method, $payment->payment_status, $order->number);
        }
    }

    private function doTheDeed($invoice, $pay = true)
    {
        $amt_to_credit = Payment::where('user_id', \Auth::user()->id)->where('payment_status', 'success')->where('payment_method', 'Credit Balance')->value('amt_to_credit');
        if ($amt_to_credit) {
            $amt_to_credit = (int) $amt_to_credit - (int) $invoice->billing_pay;
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
            if ($invoice->billing_pay && $pay) {
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
