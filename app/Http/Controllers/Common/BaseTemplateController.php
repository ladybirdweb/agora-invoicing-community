<?php

namespace App\Http\Controllers\Common;

use App\Model\Payment\Plan;
use Bugsnag;

class BaseTemplateController extends ExtendedBaseTemplateController
{
    public function ifStatement($rate, $price, $cart1, $shop1, $country = '', $state = '')
    {
        try {
            $tax_rule = $this->tax_rule->find(1);
            $product = $tax_rule->inclusive;
            $shop = $tax_rule->shop_inclusive;
            $cart = $tax_rule->cart_inclusive;
            $result = $price;
            $controller = new \App\Http\Controllers\Front\GetPageTemplateController();
            $location = $controller->getLocation();

            $country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($location['countryCode']);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['countryCode']);
            $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
            $state_code = $location['countryCode'].'-'.$location['region'];
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            $mobile_code = \App\Http\Controllers\Front\CartController::getMobileCodeByIso($location['countryCode']);
            $country_iso = $location['countryCode'];

            $geoip_country = '';
            $geoip_state = '';
            if (\Auth::user()) {
                $geoip_country = \Auth::user()->country;
                $geoip_state = \Auth::user()->state;
            }
            if ($geoip_country == '') {
                $geoip_country = \App\Http\Controllers\Front\CartController::findCountryByGeoip($country_iso);
            }
            $geoip_state_array = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            if ($geoip_state == '') {
                if (array_key_exists('id', $geoip_state_array)) {
                    $geoip_state = $geoip_state_array['id'];
                }
            }
            $result = $this->getResult($country, $geoip_country, $state, $geoip_state,
                $shop, $cart, $cart1, $shop1, $rate, $product, $price);

            return $result;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function getResult($country, $geoip_country, $state, $geoip_state,
     $shop, $cart, $cart1, $shop1, $rate, $product, $price)
    {
        if ($country == $geoip_country || $state == $geoip_state || ($country == '' && $state == '')) {
            $result = $this->getCartResult($product, $shop, $cart, $rate, $price, $cart1, $shop1);
        }

        return $result;
    }

    public function getCartResult($product, $shop, $cart, $rate, $price, $cart1, $shop1)
    {
        if ($product == 1) {
            $result = $this->getTotalSub($shop, $cart, $rate, $price, $cart1, $shop1);
        }

        if ($product == 0) {
            $result = $this->getTotalCart($shop, $cart, $rate, $price, $cart1, $shop1);
        }

        return $result;
    }

    public function getTotalCart($shop, $cart, $rate, $price, $cart1, $shop1)
    {
        if ($shop == 0 && $cart == 0) {
            $result = $this->calculateTotalcart($rate, $price, $cart1 = 0, $shop1 = 0);
        }
        if ($shop == 1 && $cart == 1) {
            $result = $this->calculateTotalcart($rate, $price, $cart1, $shop1);
        }
        if ($shop == 1 && $cart == 0) {
            $result = $this->calculateTotalcart($rate, $price, $cart1 = 0, $shop1);
        }
        if ($shop == 0 && $cart == 1) {
            $result = $this->calculateTotalcart($rate, $price, $cart = 1, $shop = 1);
        }

        return $result;
    }

    public function getTotalSub($shop, $cart, $rate, $price, $cart1, $shop1)
    {
        if ($shop == 1 && $cart == 1) {
            $result = $this->calculateTotalcart($rate, $price, $cart1 = 0, $shop1 = 0);
        }
        if ($shop == 0 && $cart == 0) {
            $result = $this->calculateSub($rate, $price, $cart1 = 1, $shop1 = 1);
        }
        if ($shop == 1 && $cart == 0) {
            $result = $this->calculateSub($rate, $price, $cart1, $shop1 = 0);
        }
        if ($shop == 0 && $cart == 1) {
            $result = $this->calculateSub($rate, $price, $cart1 = 0, $shop1);
        }

        return $result;
    }

    public function getPrice($months,$price, $currency, $value, $cost)
    {
        if ($currency == 'INR') {
            $price[$value->id] = $months  .'  ' . '₹'.' '.$cost;
        } else {
            $price[$value->id] = $months  .'  ' . '$'.' '.$cost;
        }

        return $price;
    }

    public function prices($id)
    {
        $plan = new Plan();
        $plans = $plan->where('product', $id)->orderBy('id','desc')->get();
        $price = [];
        $cart_controller = new \App\Http\Controllers\Front\CartController();
        $currency = $cart_controller->currency();

        foreach ($plans as $value) {
            $cost = $value->planPrice()->where('currency', $currency)->first()->add_price;
            $cost = \App\Http\Controllers\Front\CartController::rounding($cost);
            if ($value->days >= 366){ 
            $months = intval($value->days / 30 / 12).' '.'year';
            }elseif ($value->days < 365){
             $months = intval($value->days / 30 ).' '.'months';
        }
        else {
            $months = '';
        }
         $price = $this->getPrice($months,$price, $currency, $value, $cost);
        }
       
       
        // $this->leastAmount($id);

        return $price ;
         }
    

    public function withoutTaxRelation($productid, $currency)
    {
        try {
            $product = $this->product->findOrFail($productid);
            $controller = new \App\Http\Controllers\Front\CartController();
            $price = $controller->cost($productid);

            return $price;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function taxProcess($taxes, $price, $cart, $shop)
    {
        try {
            $rate = '';
            $tax_amount = '';
            foreach ($taxes as $tax) {
                if ($tax->compound != 1) {
                    $rate += $tax->rate;
                } else {
                    $rate = $tax->rate;
                }
                $tax_amount = $this->ifStatement($rate, $price, $cart, $shop, $tax->country, $tax->state);
            }
            // dd($tax_amount);
            return $tax_amount;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function getTaxAmount($cart, $taxes, $price, $cart1, $shop)
    {
        if ($cart == 1) {
            $tax_amount = $this->taxProcess($taxes, $price, $cart1, $shop);
        } else {
            $rate = '';
            foreach ($taxes as $tax) {
                if ($tax->compound != 1) {
                    $rate += $tax->rate;
                } else {
                    $rate = $tax->rate;
                    $price = $this->calculateTotal($rate, $price);
                }
                $tax_amount = $this->calculateTotal($rate, $price);
            }
        }

        return $tax_amount;
    }

    public function calculateTotal($rate, $price)
    {
        $tax_amount = $price * ($rate / 100);
        $total = $price + $tax_amount;

        return $total;
    }

     public function getDuration($value)
    {
     if (strpos($value, 'Y') == true){
             $duration = '/Year';
         }
         elseif(strpos($value, 'M') == true){
            $duration = '/Month';
         }
         else{
            $duration = '/One-Time';
         }
         return $duration;
    }

}
