<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Razorpay\Api\Api;
use Redirect;

class RazorpayController extends Controller
{
    public function payWithRazorpay()
    {
        $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));

        return view('themes.default1.front.checkout', compact('api'));
    }

    public function payment()
    {
        //Input items of form
        $input = Input::all();
        //get API Configuration
        $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount'=>$payment['amount']]);
            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error', $e->getMessage());

                return redirect()->back();
            }

            // Do something here for store payment details in database...
        }

        \Session::put('success', 'Payment successful, your order will be despatched in the next 48 hours.');

        return redirect()->back();
    }
}
