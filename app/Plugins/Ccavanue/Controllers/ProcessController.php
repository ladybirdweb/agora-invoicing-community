<?php

namespace App\Plugins\Ccavanue\Controllers;

use App\Http\Controllers\Controller;
use App\Plugins\Ccavanue\Model\Ccavanue;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public $ccavanue;

    public function __construct()
    {
        $ccavanue = new Ccavanue();
        $this->ccavanue = $ccavanue;
    }

    public function PassToPayment($requests)
    {
        try {
            $request = $requests['request'];
            $order = $requests['order'];
            $cart = $requests['cart'];
            if ($cart->count() > 0) {
                $total = \Cart::getSubTotal();
            } else {
                $total = $request->input('cost');
                \Cart::clear();
                \Session::set('invoiceid', $order->id);
            }

            if ($request->input('payment_gateway') == 'ccavanue') {
                if (!\Schema::hasTable('ccavenue')) {
                    throw new \Exception('Ccavanue is not configured');
                }

                $ccavanue = $this->ccavanue->where('id', 1)->first();
                if (!$ccavanue) {
                    throw new \Exception('Ccavanue Fields not given');
                }

                $orderid = $order->id;

                //dd($orderid);
                $merchant_id = $ccavanue->merchant_id;
                $redirect_url = $ccavanue->redirect_url;
                $cancel_url = $ccavanue->cancel_url;
                $name = \Auth::user()->first_name.' '.\Auth::user()->last_name;
                $address = \Auth::user()->address;
                $city = \Auth::user()->town;
                $state = \Auth::user()->state;
                $zip = \Auth::user()->zip;
                $email = \Auth::user()->email;
                $mobile = \Auth::user()->mobile;
                $country = \Auth::user()->country;
                $currency = \Auth::user()->currency;
                if (!$currency) {
                    throw new \Exception('Your currency is unknown, Please update your Profile with currency');
                }
                $ccavanue_url = $ccavanue->ccavanue_url;
                $working_key = $ccavanue->working_key;
                $access_code = $ccavanue->access_code;

                $merchant_data = 'order_id'.'='.$orderid.
                        '&amount'.'='.$total.
                        '&merchant_id'.'='.$merchant_id.
                        '&redirect_url'.'='.$redirect_url.
                        '&cancel_url'.'='.$cancel_url.
                        '&language'.'='.'EN'.
                        '&billing_name'.'='.$name.
                        '&billing_address'.'='.$address.
                        '&billing_city'.'='.$city.
                        '&billing_state'.'='.$state.
                        '&billing_zip'.'='.$zip.
                        '&billing_email'.'='.$email.
                        '&billing_tel'.'='.$mobile.
                        '&billing_country'.'='.$country.
                        '&currency'.'='.$currency.'&';
                $merchant_data = str_replace(' ', '%20', $merchant_data);
                $this->middlePage($merchant_data, $ccavanue_url, $access_code, $working_key);
            }
        } catch (\Exception $ex) {
            dd($ex);

            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function middlePage($data, $url, $access_code, $working_key)
    {
        try {
            $path = app_path().'/Plugins/Ccavanue/views';
            \View::addNamespace('plugins', $path);
            echo view('plugins::middle-page', compact('data', 'url', 'access_code', 'working_key'));
        } catch (\Exception $ex) {
        }
    }

    public function submitData($data, $url, $access_code, $working_key)
    {
        try {
            //dd($url);
            $encrypted_data = \Crypt::encrypt($data, $working_key); // Method for encrypting the data.
            echo "<form action=$url method=post name=redirect>";
            echo '<input type=hidden name=_token value=csrf_token()/>';
            echo "<input type=hidden name=encRequest value=$encrypted_data>";
            echo "<input type=hidden name=access_code value=$access_code>";
            echo '</form>';
            echo"<script language='javascript'>document.redirect.submit();</script>";
        } catch (\Exception $ex) {
            //dd($ex);
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }

    public function response(Request $request)
    {
        try {
            $url = '';
            $crypto = new Crypto();
            $ccavanue = $this->ccavanue->findOrFail(1);
            $workingKey = $ccavanue->working_key;  //Working Key should be provided here.
            $encResponse = $request->get('encResp');   //This is the response sent by the CCAvenue Server

            $rcvdString = \Crypt::decrypt($encResponse, $workingKey);  //Crypto Decryption used as per the specified working key.
            $order_status = '';
            $decryptValues = explode('&', $rcvdString);
            $dataSize = count($decryptValues);
            for ($i = 0; $i < $dataSize; $i++) {
                $information = explode('=', $decryptValues[$i]);
                if ($i == 3) {
                    $order_status = $information[1];
                }
                if ($i == 0) {
                    $order_id = $information[1];
                }
            }
            $invoiceid = $request->input('orderNo');
            $message = 'Thank you for your order. However,the transaction has been declined. Try again.';
            $status = 'fails';
            $url = 'paynow/'.$invoiceid;
            if (\Cart::getContent()->count() > 0) {
                $url = 'checkout';
            }
            \Session::forget('invoiceid');
            if ($order_status == 'Success') {
                $control = new \App\Http\Controllers\Order\RenewController();
                if ($control->checkRenew() == false) {
                    $invoice = new \App\Model\Order\Invoice();
                    $invoice = $invoice->findOrFail($invoiceid);
                    $checkout_controller = new \App\Http\Controllers\Front\CheckoutController();
                    $checkout_controller->checkoutAction($invoice);
                } else {
                    $control->successRenew();
                }
                \Cart::clear();
                $status = 'success';
                $message = 'Thank you for your order. Your transaction is successful. We will be processing your order soon.';
            }

            return redirect($url)->with($status, $message);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
    }
}
