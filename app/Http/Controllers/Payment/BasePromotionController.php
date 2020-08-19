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
            $price = intval($price);
            switch ($type) {
                case 1://Percentage
                    $percentage = $price * (intval($value) / 100);

                    return  $price - $percentage;
                case 2://Fixed amount
                    return $price - $value;
                case 3://Price Override
                    \Cart::update($productid, [
                        'price' => $value,
                    ]);

                    return '-0';
                case 4://Free setup
                    return '-'.$price;
            }
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-cost-error'));
        }
    }

    public function getPromotionDetails($code)
    {
        $promo = Promotion::where('code', $code)->first();
        //check promotion code is valid
        if (! $promo) {
            throw new \Exception('Invalid promo code');
        }
        $relation = $promo->relation()->get();
        //check the relation between code and product
        if (count($relation) == 0) {
            throw new \Exception(\Lang::get('message.no-product-related-to-this-code'));
        }
        //check the usess
        $cont = new \App\Http\Controllers\Payment\PromotionController();
        $uses = $cont->checkNumberOfUses($code);

        if ($uses != 'success') {
            throw new \Exception(\Lang::get('message.usage-of-code-completed'));
        }
        //check for the expiry date
        $expiry = $this->checkExpiry($code);
        if ($expiry != 'success') {
            throw new \Exception(\Lang::get('message.usage-of-code-expired'));
        }

        return $promo;
    }

    public function findCostAfterDiscount($promoid, $productid, $userid)
    {
        try {
            $promotion = Promotion::findOrFail($promoid);
            $cart_control = new \App\Http\Controllers\Front\CartController();
            $currency = $cart_control->checkCurrencySession();
            if ($cart_control->checkPlanSession() === true) {
                $planid = \Session::get('plan');
            }
            $price = $cart_control->planCost($productid, $userid, $planid = '');
            $updated_price = $this->findCost($promotion->type, $promotion->value, $price, $productid);

            return $updated_price;
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.find-discount-error'));
        }
    }
}
