<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
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
     * @param type $productid
     * @param type $userid
     *
     * @throws \Exception
     *
     * @return type
     */
    public function productCost($productid, $userid = '')
    {
        try {
            $sales = 0;
            $currency = $this->currency($userid);
            $product = Product::find($productid);

            // $price = $product->price()->where('currency', $currency)->first();
            $plan_id = Plan::where('product', $productid)->pluck('id')->first();
            $price = PlanPrice::where('plan_id', $plan_id)->where('currency', $currency)->pluck('add_price')->first();
            if ($price) {
                $sales = $price;
                // if ($sales == 0) {
                //     $sales = $price->price;
                // }
            }
            //}

            return $sales;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * @param type $productid
     * @param type $userid
     * @param type $planid
     *
     * @throws \Exception
     *
     * @return type
     */
    public function planCost($productid, $userid, $planid = '')
    {
        try {
            $cost = 0;
            $subscription = $this->allowSubscription($productid);
            if ($this->checkPlanSession() === true) {
                $planid = Session::get('plan');
            }

            if ($subscription === true) {
                $plan = new \App\Model\Payment\Plan();
                $plan = $plan->where('id', $planid)->where('product', $productid)->first();
                $items = (\Session::get('items'));

                if ($plan) {
                    $currency = $this->currency($userid);
                    $price = $plan->planPrice()
                                    ->where('currency', $currency)
                                    ->first()
                            ->add_price;
                    $days = $plan->days;
                    $months = $days / 30 / 12;
                    if ($items != null) {
                        $cost = $items[$productid]['price'];
                    } else {
                        $cost = round($months) * $price;
                    }
                }
            }

            return $cost;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function getGeoipCountry($country_iso)
    {
        $geoip_country = '';
        if (\Auth::user()) {
            $geoip_country = \Auth::user()->country;
        }
        if ($geoip_country == '') {
            $geoip_country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($country_iso);
        }

        return $geoip_country;
    }

    public function getGeoipState($state_code)
    {
        $geoip_state = '';
        $geoip_state_array = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
        if (\Auth::user()) {
            $geoip_state = \Auth::user()->state;
        }
        if ($geoip_state == '') {
            if (array_key_exists('id', $geoip_state_array)) {
                $geoip_state = $geoip_state_array['id'];
            }
        }

        return $geoip_state;
    }

    /**
     * When from same Indian State.
     */
    public function getTaxWhenIndianSameState($user_state, $origin_state, 
        $productid, $c_gst, $s_gst, $state_code, $status)
    {
        $taxClassId = TaxClass::where('name', 'Intra State GST')->pluck('id')->toArray(); //Get the class Id  of state
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes);

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
     * When from other Indian State.
     */
    public function getTaxWhenIndianOtherState($user_state, $origin_state, $productid, $i_gst, $state_code, $status)
    {
        $taxClassId = TaxClass::where('name', 'Inter State GST')->pluck('id')->toArray(); //Get the class Id  of state
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForOtherState($productid, $i_gst, $taxClassId, $taxes);
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
     * When from Union Territory.
     */
    public function getTaxWhenUnionTerritory($user_state, $origin_state, 
        $productid, $c_gst, $ut_gst, $state_code, $status)
    {
        $taxClassId = TaxClass::where('name', 'Union Territory GST')->pluck('id')->toArray(); //Get the class Id  of state
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
        $taxForAnyCountry = $this->getTaxForAnyCountry($taxClassId, $productid, $status);
        $taxes = $this->getTaxByPriority($taxClassId);
        $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
        if ($value == '') {
            $status = 0;
        }
        $rate = $value;

        return ['taxes'=>$taxes, 'status'=>$status, 'value'=>$value, 'rate'=>$value];
    }
}
