<?php

namespace App\Http\Controllers\Common;

use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use Bugsnag;

class BaseTemplateController extends ExtendedBaseTemplateController
{
    public function getPrice($months, $price, $priceDescription, $value, $cost, $currency)
    {
        $price1 = currency_format($cost, $code = $currency);
        $price[$value->id] = $months.'  '.$price1.' '.$priceDescription;

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
                } else {
                    $def_currency = Setting::find(1)->default_currency;
                    $def_currency_symbol = Setting::find(1)->default_symbol;
                    $currency = \Auth::user()->update(['currency' => $def_currency]);
                    $symbol = \Auth::user()->update(['currency_symbol'=>$def_currency_symbol]);
                }
                $priceDescription = $value->planPrice->first();
                $priceDescription = $priceDescription ? $priceDescription->price_description : '';
                $cost = \App\Http\Controllers\Front\CartController::rounding($cost);
                $duration = $value->periods;
                $months = count($duration) > 0 ? $duration->first()->name : '';
                $price = $this->getPrice($months, $price, $priceDescription, $value, $cost, $currency);
                // $price = currency_format($cost, $code = $currency);
            }

            return $price;
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
