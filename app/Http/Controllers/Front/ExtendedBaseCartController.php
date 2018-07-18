<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
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
}
