<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use Bugsnag;
use Cart;
use Session;

class ExtendedBaseCartController extends Controller
{
    

    /**
     * @return type
     */
    public function clearCart()
    {
        foreach (Cart::getContent() as $item) {
            Cart::clearItemConditions($item->id);
            if (\Session::has('domain'.$item->id)) {
                \Session::forget('domain'.$item->id);
            }
        }

        $this->removePlanSession();
        $renew_control = new \App\Http\Controllers\Order\RenewController();
        $renew_control->removeSession();
        Cart::clearCartConditions();
        Cart::clear();

        return redirect('show/cart');
    }

    /**
     * @throws \Exception
     */
    public function removePlanSession()
    {
        try {
            if (Session::has('plan')) {
                Session::forget('plan');
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function checkPlanSession()
    {
        try {
            if (Session::has('plan')) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $iso
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function getMobileCodeByIso($iso)
    {
        try {
            $code = '';
            if ($iso != '') {
                $mobile = \DB::table('mobile')->where('iso', $iso)->first();
                if ($mobile) {
                    $code = $mobile->phonecode;
                }
            }

            return $code;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }



    public function getGeoipCountry($country_iso, $user_country = '')
    {
        $geoip_country = '';
        if (\Auth::user()) {
            if (\Auth::user()->role == 'admin') {
                $geoip_country = $user_country;
            } elseif (\Auth::user()->role == 'user') {
                $geoip_country = \Auth::user()->country;
            }

            if ($geoip_country == '') {
                $geoip_country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($country_iso);
            }
        }

        return $geoip_country;
    }

    public function getGeoipState($state_code, $user_state = '')
    {
        $geoip_state = '';
        if (\Auth::user()) {
            if (\Auth::user()->role == 'admin') {
                $geoip_state = $user_state;
            } elseif (\Auth::user()->role == 'user') {
                $geoip_state = \Auth::user()->state;
            }
            if ($geoip_state == '') {
                $geoip_state_array = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
                if (array_key_exists('id', $geoip_state_array)) {
                    $geoip_state = $geoip_state_array['id'];
                }
            }
        }

        return $geoip_state;
    }
}
