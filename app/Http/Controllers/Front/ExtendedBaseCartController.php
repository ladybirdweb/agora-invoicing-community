<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Payment\Plan;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
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
            $code = \Input::get('coupon');
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
            if ($this->checkPlanSession() === true) {
                $planid = Session::get('plan');
            }
            if (!$planid) {//When Product Is Added from Cart
                $planid = Plan::where('product', $productid)->pluck('id')->first();
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
            }

            return $cost;
        } catch (\Exception $ex) {
            dd($ex);

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

    /**
     * When from same Indian State.
     */
    public function getTaxWhenIndianSameState($user_state, $origin_state, $productid, $c_gst, $s_gst, $state_code, $status)
    {
        $taxes = [0];
        $value = '';
        $taxClassId = TaxClass::where('name', 'Intra State GST')->pluck('id')->toArray(); //Get the class Id  of state
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes);
            if ($value == 0) {
                $status = 0;
            }
        }

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value];
    }

    /**
     * When from other Indian State.
     */
    public function getTaxWhenIndianOtherState($user_state, $origin_state, $productid, $i_gst, $state_code, $status)
    {
        $taxes = [0];
        $value = '';
        $taxClassId = TaxClass::where('name', 'Inter State GST')->pluck('id')->toArray(); //Get the class Id  of state
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForOtherState($productid, $i_gst, $taxClassId, $taxes);
            if ($value == '') {
                $status = 0;
            }
        }

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value];
    }

    /**
     * When from Union Territory.
     */
    public function getTaxWhenUnionTerritory(
        $user_state,
        $origin_state,
        $productid,
        $c_gst,
        $ut_gst,
        $state_code,
        $status
    ) {
        $taxClassId = TaxClass::where('name', 'Union Territory GST')
        ->pluck('id')->toArray(); //Get the class Id  of state
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForUnionTerritory($productid, $c_gst, $ut_gst, $taxClassId, $taxes);
            if ($value == '') {
                $status = 0;
            }
        } else {
            $taxes = [0];
            $value = '';
        }

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value];
    }

    /**
     * When from Other Country and tax is applied for that country or state.
     */
    public function getTaxForSpecificCountry($taxClassId, $productid, $status)
    {
        $taxes = $this->getTaxByPriority($taxClassId);
        $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
        if ($value == '') {
            $status = 0;
        }
        $rate = $value;

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value, 'rate'=>$value];
    }

    /**
     * When from Other Country and tax is applied for Any country and state.
     */
    public function getTaxForAnyCountry($taxClassId, $productid, $status)
    {
        $taxes = $this->getTaxByPriority($taxClassId);
        $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
        if ($value == '') {
            $status = 0;
        }
        $rate = $value;

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value, 'rate'=>$value];
    }
}
