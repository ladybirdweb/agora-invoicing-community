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
    public function addCouponUpdate()
    {
        try {
            $code = \Request::get('coupon');
            $cart = Cart::getContent();
            $id = '';
            foreach ($cart as $item) {
                $id = $item->id;
            }
            $promo_controller = new \App\Http\Controllers\Payment\PromotionController();
            $result = $promo_controller->checkCode($code, $id);
            if ($result == 'success') {
                return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
            }

            return redirect()->back();
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @return type
     */
    public function clearCart()
    {
        foreach (Cart::getContent() as $item) {
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

    /**
     * Get Cost For a particular Plan.
     *
     * @param int $productid
     * @param int $userid
     * @param int $planid
     *
     * @throws \Exception
     *
     * @return int
     */
    public function planCost($productid, $userid, $planid = '')
    {
        try {
            $cost = 0;
            $months = 0;
            $cont = new CartController();
            $currency = $cont->currency($userid);
            if (! $planid) {//When Product Is Added from Cart
                $planid = Plan::where('product', $productid)->pluck('id')->first();
            } elseif ($this->checkPlanSession() === true && ! $planid) {
                $planid = Session::get('plan');
            }
            $plan = Plan::where('id', $planid)->where('product', $productid)->first();
            if ($plan) { //Get the Total Plan Cost if the Plan Exists For a Product
                $months = 1;
                $cont = new CartController();
                $currency = $cont->currency($userid);
                $product = Product::find($productid);
                $days = $plan->periods->pluck('days')->first();
                $price = ($product->planRelation->find($planid)->planPrice->where('currency', $currency['currency'])->first()->add_price);
                if ($days) { //If Period Is defined for a Particular Plan ie no. of Days Generated
                    $months = $days >= '365' ? $days / 30 / 12 : $days / 30;
                }
                $finalPrice = str_replace(',', '', $price);
                $cost = round($months) * $finalPrice;
            } else {
                throw new \Exception('Product cannot be added to cart. No such plan exists.');
            }

            return $cost;
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
