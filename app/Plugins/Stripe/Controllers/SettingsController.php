<?php

namespace App\Plugins\Stripe\Controllers;

use App\ApiKey;
use App\Http\Controllers\Common\CronController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncBillingToLatestVersion;
use App\Plugins\Stripe\Model\StripePayment;
use App\Traits\Payment\PostPaymentHandle;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Illuminate\Http\Request;
use Schema;
use Validator;

class SettingsController extends Controller
{
    use PostPaymentHandle;

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
        try {
            $invoice = \Session::get('invoice');
            $amount = rounding(\Cart::getTotal()) ?: rounding(\Session::get('totalToBePaid'));
            $currency = strtolower($invoice->currency);
            $url = url('/confirm/payment');
            $confirm = $this->handlePayment($request, $amount, $currency, $url, $invoice);
            if ($confirm->status === 'succeeded') {
                $result = $this->processPaymentSuccess($invoice, $currency);
                \Session::forget(['items', 'code', 'codevalue', 'totalToBePaid', 'invoice', 'cart_currency']);
                \Cart::removeCartCondition('Processing fee');

                return redirect('checkout')->with($result['status'], $result['message']);
            } else {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($confirm['id']);
                $redirectUrl = $paymentIntent->next_action->redirect_to_url->url;

                return redirect()->away($redirectUrl);
            }
        } catch (\Cartalyst\Stripe\Exception\ApiLimitExceededException|\Cartalyst\Stripe\Exception\BadRequestException|\Cartalyst\Stripe\Exception\MissingParameterException|\Cartalyst\Stripe\Exception\NotFoundException|\Cartalyst\Stripe\Exception\ServerErrorException|\Cartalyst\Stripe\Exception\StripeException|\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            $control = new \App\Http\Controllers\Order\RenewController();
            if ($control->checkRenew($invoice->is_renewed) != true) {
                return redirect('checkout')->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
            } else {
                return redirect('paynow/'.$invoice->id)->with('fails', 'Your Payment was declined. '.$e->getMessage().'. Please try again or try the other gateway');
            }
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

    public function handlePayment($request, $amount, $currency, $url, $invoice = null)
    {
        $validator = Validator::make($request->all(), []);
        $validation = [
            'card_no' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvv' => 'required',
        ];
        try {
            $this->validate($request, $validation);

            $cronController = new CronController();
            $cost = $this->calculateUnitCost($currency, $amount);

            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            \Stripe\Stripe::setApiKey($stripeSecretKey);
            $customer = \Stripe\Customer::create([
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

            $paymentMethod = \Stripe\PaymentMethod::create([
                'type' => 'card',
                'card' => [
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('exp_month'),
                    'exp_year' => $request->get('exp_year'),
                    'cvc' => $request->get('cvv'),
                ],
            ]);

            $intent = \Stripe\PaymentIntent::create([
                'amount' => intval($cost),
                'currency' => $currency,
                'payment_method' => $paymentMethod['id'],
                'customer' => $customer['id'],
                'confirmation_method' => 'automatic',
                'setup_future_usage' => 'off_session',
                'description' => 'payments for the purchased product',

            ]);
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            $confirm = $stripe->paymentIntents->confirm(
                $intent['id'],
                [
                    'payment_method' => $paymentMethod['id'],
                    'return_url' => $url,
                ]
            );

            return $confirm;
        } catch(\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function handleStripeAutoPay($stripe_payment_details, $product_details, $unit_cost, $currency, $plan)
    {
        try {
            $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();
            $stripe = new \Stripe\StripeClient($stripeSecretKey);
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            $paymentMethod = \Stripe\PaymentMethod::retrieve($stripe_payment_details->payment_intent_id);

            //create product
            $product = $stripe->products->create([
                'name' => $product_details->name,
            ]);
            $product_id = $product['id'];

            //define product price and recurring interval

            $price = $stripe->prices->create([
                'unit_amount' => $unit_cost,
                'currency' => $currency,
                'recurring' => ['interval' => 'day', 'interval_count' => $plan->days],
                'product' => $product_id,
            ]);
            $price_id = $price['id'];

            //CREATE SUBSCRIPTION

            $stripe_subscription = $stripe->subscriptions->create([
                'customer' => $paymentMethod->customer,
                'items' => [
                    ['price' => $price_id],
                ],
                'default_payment_method' => $paymentMethod->id,
            ]);

            return $stripe_subscription;
        } catch (ApiErrorException $e) {
            $errorCode = $e->getStripeCode();
            $errorMessage = $e->getMessage();
            Log::error("Stripe API Error: $errorCode - $errorMessage");
        }
    }
}
