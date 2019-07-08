<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Model\Payment\Promotion;
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
            if ($cart_control->checkPlanSession() === true) {
                $planid = \Session::get('plan');
            }
            if (count(\Cart::getContent())) {
                $product_price = $cart_control->planCost($productid, $userid, $planid = '');
            }
            $updated_price = $this->findCost($promotion_type, $promotion_value, $product_price, $productid);

            return $updated_price;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-discount-error'));
        }
    }
}
