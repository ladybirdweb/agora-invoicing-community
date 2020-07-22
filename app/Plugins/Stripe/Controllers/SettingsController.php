<?php

namespace App\Plugins\Stripe\Controllers;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Setting;
use App\Plugins\Stripe\Model\StripePayment;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Schema;
use Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except'=>['postPaymentWithStripe']]);
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
                \Artisan::call('db:seed', ['--class' => 'database\\seeds\\StripeSupportedCurrencySeeder', '--force' => true]);
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
            'business'    => 'required',
            'cmd'         => 'required',
            'paypal_url'  => 'required|url',
            'success_url' => 'url',
            'cancel_url'  => 'url',
            'notify_url'  => 'url',
            'currencies'  => 'required',
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
            Stripe::where('id', $currencies->id)->update(['base_currency'=>$baseCurrency]);
        }

        return ['message' => 'success', 'update'=>'Base Currency Updated'];
    }

    public function updateApiKey(Request $request)
    {
        try {
            $stripe = Stripe::make($request->input('stripe_secret'));
            $response = $stripe->customers()->create(['description' => 'Test Customer to Validate Secret Key']);
            $stripe_secret = $request->input('stripe_secret');
            ApiKey::find(1)->update(['stripe_secret'=>$stripe_secret]);

            return successResponse(['success'=>'true', 'message'=>'Secret key updated successfully']);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException  $e) {
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
        $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
        $stripe = Stripe::make($stripeSecretKey);
        try {
            $invoice = \Session::get('invoice');
            $amount = \Cart::getTotal();
            if (! $amount) {//During renewal
                if ($request->input('amount') != $invoice->grand_total) {
                    throw new \Exception('Invalid modification of data');
                }
                $amount = $request->input('amount');
            }
            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            $stripe = Stripe::make($stripeSecretKey);
            $token = $stripe->tokens()->create([
                'card' => [
                    'number'    => $request->get('card_no'),
                    'exp_month' => $request->get('exp_month'),
                    'exp_year'  => $request->get('exp_year'),
                    'cvc'       => $request->get('cvv'),
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
            $currency = strtolower(\Auth::user()->currency);
            $card = $stripe->cards()->create($stripeCustomerId, $token['id']);
            $charge = $stripe->charges()->create([
                'customer' => $customer['id'],
                'currency' => $currency,
                'amount'   =>$amount,
                'description' => 'Add in wallet',
            ]);
            if ($charge['status'] == 'succeeded') {
                //Change order Status as Success if payment is Successful
                $stateCode = \Auth::user()->state;
                $cont = new \App\Http\Controllers\RazorpayController();
                $state = $cont->getState($stateCode);
                $currency = $cont->getCurrency();

                $control = new \App\Http\Controllers\Order\RenewController();
                //After Regular Payment
                if ($control->checkRenew() === false) {
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);

                    $view = $cont->getViewMessageAfterPayment($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                    \Session::forget('items');
                    \Session::forget('code');
                    \Session::forget('codevalue');
                } else {
                    //Afer Renew
                    $control->successRenew($invoice);
                    $payment = new \App\Http\Controllers\Order\InvoiceController();
                    $payment->postRazorpayPayment($invoice);
                    if ($invoice->grand_total) {
                        $this->sendPaymentSuccessMailtoAdmin($invoice->currency, $invoice->grand_total, \Auth::user(), $invoice->invoiceItem()->first()->product_name);
                    }
                    $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                }

                return redirect('checkout')->with($status, $message);
            } else {
                return redirect('checkout')->with('fails', 'Your Payment was declined. Please try making payment with other gateway');
            }
        } catch (\Cartalyst\Stripe\Exception\ApiLimitExceededException | \Cartalyst\Stripe\Exception\BadRequestException | \Cartalyst\Stripe\Exception\MissingParameterException | \Cartalyst\Stripe\Exception\NotFoundException | \Cartalyst\Stripe\Exception\ServerErrorException | \Cartalyst\Stripe\Exception\StripeException | \Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            $this->sendFailedPaymenttoAdmin($request['amount'], $e->getMessage());

            return redirect('checkout')->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $this->sendFailedPaymenttoAdmin($request['amount'], $e->getMessage());
            \Session::put('amount', $request['amount']);
            \Session::put('error', $e->getMessage());

            return redirect()->route('checkout');
        } catch (\Exception $e) {
            return redirect('checkout')->with('fails', 'Your payment was declined. '.$e->getMessage().'. Please try again or try the other gateway.');
        }
    }

    public static function sendFailedPaymenttoAdmin($amount, $exceptionMessage)
    {
        $setting = Setting::find(1);
        $paymentFailData = 'Payment for'.' '.'of'.' '.\Auth::user()->currency.' '.$amount.' '.'failed by'.' '.\Auth::user()->first_name.' '.\Auth::user()->last_name.' '.'. User Email:'.' '.\Auth::user()->email.'<br>'.'Reason:'.$exceptionMessage;
        $templateController = new \App\Http\Controllers\Common\TemplateController();
        $templateController->mailing($setting->email, $setting->company_email, $paymentFailData, 'Paymemt failed');
    }

    public static function sendPaymentSuccessMailtoAdmin($currency, $total, $user, $productName)
    {
        $setting = Setting::find(1);
        $templateController = new \App\Http\Controllers\Common\TemplateController();
        $paymentSuccessdata = 'Payment for'.' '.$productName.' '.'of'.' '.$currency.' '.$total.' '.'successful by'.' '.$user->first_name.' '.$user->last_name.' '.'Email:'.' '.$user->email;
        $templateController->mailing($setting->email, $setting->company_email, $paymentSuccessdata, 'Payment Successful ');
    }
}
