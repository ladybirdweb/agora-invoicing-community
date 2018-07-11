<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Product\Product;

class BasePromotionController extends Controller
{
    
    public function getCode()
    {
        try {
            $code = str_random(6);
            echo strtoupper($code);
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }


    public function findCost($type, $value, $price, $productid)
    {
        try {
            switch ($type) {
                case 1:
                    $percentage = $price * ($value / 100);
                    return  $price - $percentage;
                case 2:
                    return $price - $value;
                case 3:
                    \Cart::update($productid, [
                        'price' => $value,
                    ]);

                    return '-0';
                case 4:
                    return '-'.$price;
            }
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-cost-error'));
        }
    }


        public function findCostAfterDiscount($promoid, $productid)
    {
        try {
            $promotion = Promotion::findOrFail($promoid);
            $product = Product::findOrFail($productid);
            $promotion_type = $promotion->type;
            $promotion_value = $promotion->value;
            $product_price = 0;
            $userid = \Auth::user()->id;
            $control = new \App\Http\Controllers\Order\RenewController();
            $cart_control = new \App\Http\Controllers\Front\CartController();
            $currency = $cart_control->checkCurrencySession();
            if ($cart_control->checkPlanSession() == true) {
                $planid = \Session::get('plan');
            }
            if ($product->subscription != 1) {
                $planId = Plan::where('product', $productid)->pluck('id')->first();
                $product_price = PlanPrice::where('plan_id', $planId)->where('currency', $currency)->pluck('add_price')->first();
            } else {
                $product_price = $control->planCost($planid, $userid);
            }
            if (count(\Cart::getContent())) {
                $product_price = \Cart::getSubTotalWithoutConditions();
                // dd($product_price);
            }
            $updated_price = $this->findCost($promotion_type, $promotion_value, $product_price, $productid);
            // dd($updated_price);
            //dd([$product_price,$promotion_type,$updated_price]);
            return $updated_price;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-discount-error'));
        }
    }
}
