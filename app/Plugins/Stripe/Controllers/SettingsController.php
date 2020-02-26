<?php

namespace App\Plugins\Stripe\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Stripe\Model\Stripe;
use Illuminate\Http\Request;
use App\ApiKey;
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

            $stripe1 = new Stripe();
            // //dd($ccavanue);
            $stripe = $stripe1->where('id', '1')->first();

            if (!$stripe) {
                \Artisan::call('db:seed',['--class' => 'database\\seeds\\StripeSupportedCurrencySeeder', '--force' => true]);
            }
            $allCurrencies = Stripe::pluck('supported_currencies','id')->toArray();
             $apikey = new ApiKey();
            $stripeKeys = $apikey->select('stripe_key', 'stripe_secret')->first();
            $baseCurrency = Stripe::pluck('base_currency')->toArray();
            $path = app_path().'/Plugins/Stripe/views';
            \View::addNamespace('plugins', $path);
            return view('plugins::settings', compact('stripe','baseCurrency','allCurrencies','stripeKeys'));
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
        $baseCurrency = Stripe::where('id',$request->input('b_currency'))->pluck('supported_currencies')->first();
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
    public function stripePost(Request $request)
    {dd($request->all());
        $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
         $customer = $stripe::customers()->create([
            'email' => 'patrick@gmail.com',
        ]);
         $stripeCustomerId= $customer['id'];
         $card = $stripe->cards()->create($stripeCustomerId, $request->stripeToken);
         // dd($stripeCustomerId);
        \Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "customer" => 'cus_GnwaDgtCn10rag',
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com." 
        ]);
  
        Session::flash('success', 'Payment successful!');
          
        return back();
    }
}
