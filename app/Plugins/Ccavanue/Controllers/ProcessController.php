<?php

namespace App\Plugins\Ccavanue\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plugins\Ccavanue\Model\Ccavanue;
use App\Plugins\Ccavanue\Controllers\Crypto;

class ProcessController extends Controller {

    public $ccavanue;

    public function __construct() {
        $ccavanue = new Ccavanue();
        $this->ccavanue = $ccavanue;
    }

    public function PassToPayment($requests) {

        try {

            $request = $requests['request'];
            $order = $request['order'];

            if ($request->input('payment_gateway') == 'ccavenue') {

                if (!\Schema::hasTable('ccavanue')) {
                    throw new \Exception('Ccavanue is not configured');
                }
                $ccavanue = $this->ccavanue->where('id', 1)->first();
                if (!$ccavanue) {
                    throw new \Exception('Ccavanue Fields not given');
                }
                $orderid = 1; //$order->id;
                $total = \Cart::getSubTotal();

                $merchant_id = $ccavanue->merchant_id;
                $redirect_url = $ccavanue->redirect_url;
                $cancel_url = $ccavanue->cancel_url;
                $name = $request->input('first_name') . ' ' . $request->input('last_name');
                $address = $request->input('address');
                $city = $request->input('address');
                $state = $request->input('state');
                $zip = $request->input('zip');
                $email = $request->input('email');
                $mobile = $request->input('mobile');
                $country = $request->input('country');
                $currency = $request->input('currency');


                $ccavanue_url = $ccavanue->ccavanue_url;
                $working_key = $ccavanue->working_key;
                $access_code = $ccavanue->access_code;


                $merchant_data = 'order_id' . '=' . $orderid .
                        '&amount' . '=' . $total .
                        '&merchant_id' . '=' . $merchant_id .
                        '&redirect_url' . '=' . $redirect_url .
                        '&cancel_url' . '=' . $cancel_url .
                        '&language' . '=' . 'EN' .
                        '&billing_name' . '=' . $name .
                        '&billing_address' . '=' . $address .
                        '&billing_city' . '=' . $city .
                        '&billing_state' . '=' . $state .
                        '&billing_zip' . '=' . $zip .
                        '&billing_email' . '=' . $email .
                        '&billing_tel' . '=' . $mobile .
                        '&billing_country' . '=' . $country .
                        '&currency' . '=' . $currency . '&';
                $merchant_data = str_replace(" ", '%20', $merchant_data);
                $this->SubmitData($merchant_data, $ccavanue_url, $access_code, $working_key);
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function SubmitData($data, $url, $access_code, $working_key) {
        try {
            $crypto = new Crypto;
            $encrypted_data = $crypto->encrypt($data, $working_key); // Method for encrypting the data.
            echo "<form action=$url method=post name=redirect>";
            echo "<input type=hidden name=_token value=csrf_token()/>";
            echo "<input type=hidden name=encRequest value=$encrypted_data>";
            echo "<input type=hidden name=access_code value=$access_code>";
            echo "</form>";
            echo"<script language='javascript'>document.redirect.submit();</script>";
        } catch (\Exception $ex) {
            //dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }
    
    public function Response(Request $request){
        
        try{
            dd($request);
        } catch (\Exception $ex) {
             throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
        
    }

}
