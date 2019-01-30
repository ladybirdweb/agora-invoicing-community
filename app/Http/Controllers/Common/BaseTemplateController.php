<?php

namespace App\Http\Controllers\Common;

use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use Bugsnag;

class BaseTemplateController extends ExtendedBaseTemplateController
{
    public function getPrice($months, $price, $priceDescription, $value, $cost, $symbol)
    {
        $price[$value->id] = $months.'  '.$symbol.$cost.' '.$priceDescription;

        return $price;
    }

    public function prices($id)
    {
        try {
            $plans = Plan::where('product', $id)->orderBy('id', 'desc')->get();
            $price = [];
            $cart_controller = new \App\Http\Controllers\Front\CartController();
            $currencyAndSymbol = $cart_controller->currency();
            $currency = $currencyAndSymbol['currency'];
            $symbol = $currencyAndSymbol['symbol'];
            if ($symbol == '') {  //If user has no currency symbol(In case of old customers)
                $symbol = Currency::where('code', $currency)->pluck('symbol')->first();
                $symbol = \Auth::user()->update(['currency_symbol'=> $symbol]);
            }
            foreach ($plans as $value) {
                $cost = $value->planPrice()->where('currency', $currency)->first();
                if ($cost) {
                    $cost = $cost->add_price;
                }
                $priceDescription = $value->planPrice->first();
                $priceDescription = $priceDescription ? $priceDescription->price_description : '';
                $cost = \App\Http\Controllers\Front\CartController::rounding($cost);
                $duration = $value->periods;
                $months = count($duration) > 0 ? $duration->first()->name : '';
                $price = $this->getPrice($months, $price, $priceDescription, $value, $cost, $symbol);
            }

            return $price;
        } catch (\Exception $ex) {
            dd($ex);
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
