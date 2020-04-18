<?php

namespace App\Plugins\Stripe\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Stripe\Model\StripePayment;
use Illuminate\Http\Request;
use App\ApiKey;
use Validator;
use App\Model\Order\Invoice;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Stripe\Error\Card;
use Schema;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
    }

    public function Settings()
    {
        try {
            if (!Schema::hasTable('stripe')) {
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

            if (!$stripe) {
                \Artisan::call('db:seed',['--class' => 'database\\seeds\\StripeSupportedCurrencySeeder', '--force' => true]);
            }
            $allCurrencies = StripePayment::pluck('currencies','id')->toArray();
             $apikey = new ApiKey();
            $stripeKeys = $apikey->select('stripe_key', 'stripe_secret')->first();
            $baseCurrency = StripePayment::pluck('base_currency')->toArray();
            $path = app_path().'/Plugins/Stripe/views';
            \View::addNamespace('plugins', $path);
            return view('plugins::settings', compact('stripe','baseCurrency','allCurrencies','stripeKeys'));
        } catch (\Exception $ex) {
            dd($ex);
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
        $baseCurrency = Stripe::where('id',$request->input('b_currency'))->pluck('currencies')->first();
        $allCurrencies = Stripe::select('base_currency','id')->get();
        foreach ($allCurrencies as $currencies) {
           Stripe::where('id',$currencies->id)->update(['base_currency'=>$baseCurrency]);
        }
        return ['message' => 'success', 'update'=>'Base Currency Updated'];
    }

    public function updateApiKey(Request $request)
    {
         $stripe_key = $request->input('stripe_key');
        $stripe_secret = $request->input('stripe_secret');
        ApiKey::find(1)->update(['stripe_key'=>$stripe_key, 'stripe_secret'=>$stripe_secret]);

        return ['message' => 'success', 'update'=>'Stripe keys Updated'];
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
       $amount = \Session::get('amount');
        $stripeSecretKey = ApiKey::pluck('stripe_secret')->first();   
        $stripe = Stripe::make($stripeSecretKey);
        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number'    => $request->get('card_no'),
                    'exp_month' => $request->get('exp_month'),
                    'exp_year'  => $request->get('exp_year'),
                    'cvc'       => $request->get('cvv'),
                ],
            ]);
            if (!isset($token['id'])) {
                \Session::put('error','The Stripe Token was not generated correctly');
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
            $stripeCustomerId= $customer['id'];
            $currency = strtolower(\Auth::user()->currency);
            $card = $stripe->cards()->create($stripeCustomerId, $token['id']); 
            $charge = $stripe->charges()->create([
                'customer' => $customer['id'],
                'currency' => $currency,
                'amount'   =>$amount,
                'description' => 'Add in wallet',
            ]);
            if($charge['status'] == 'succeeded') {
                try {
                //Change order Status as Success if payment is Successful
                $stateCode = \Auth::user()->state;
                $cont = new \App\Http\Controllers\RazorpayController();
                $state = $cont->getState($stateCode);
                $currency = $cont->getCurrency();
                 $invoice = \Session::get('invoice');
                // $invoice = Invoice::where('id', $invoice)->first();
                // dd($invoice);
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
                    $payment->postRazorpayPayment($invoice->id, $invoice->grand_total);
                    $view = $cont->getViewMessageAfterRenew($invoice, $state, $currency);
                    $status = $view['status'];
                    $message = $view['message'];
                }

                return redirect('checkout')->with($status, $message);
            } catch (\Exception $ex) {
                throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
            }

                \Session::put('success','Money add successfully in wallet');
                return redirect()->route('stripform');
            } else {
                \Session::put('fails','Your Payment was declined. Do you want to try making payment with the other gateway?');
                return redirect()->route('checkout');
            }
        } catch (Exception $e) {
            \Session::put('amount',$request['amount']);
            \Session::put('error',$e->getMessage());
            return redirect()->route('stripform');
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            \Session::put('amount',$request['amount']);
            \Session::put('error',$e->getMessage());
            return redirect()->route('stripform');
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            \Session::put('amount',$request['amount']);
            \Session::put('error',$e->getMessage());
            return redirect()->route('stripform');
        }
    }
}
