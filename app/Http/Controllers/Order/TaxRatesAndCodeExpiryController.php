<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CartController;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;

class TaxRatesAndCodeExpiryController extends Controller
{
    /**
     *Tax When state is not empty.
     */
    public function getTaxWhenState($user_state, $productid, $origin_state)
    {
        $cartController = new CartController();
        $c_gst = $user_state->c_gst;
        $s_gst = $user_state->s_gst;
        $i_gst = $user_state->i_gst;
        $ut_gst = $user_state->ut_gst;
        $state_code = $user_state->state_code;
        if ($state_code == $origin_state) {//If user and origin state are same
             $taxClassId = TaxClass::where('name', 'Intra State GST')->pluck('id')->toArray(); //Get the class Id  of state
               if ($taxClassId) {
                   $taxes = $cartController->getTaxByPriority($taxClassId);
                   $value = $cartController->getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes);
               } else {
                   $taxes = [0];
               }
        } elseif ($state_code != $origin_state && $ut_gst == 'NULL') {//If user is from other state

            $taxClassId = TaxClass::where('name', 'Inter State GST')->pluck('id')->toArray(); //Get the class Id  of state
            if ($taxClassId) {
                $taxes = $cartController->getTaxByPriority($taxClassId);
                $value = $cartController->getValueForOtherState($productid, $i_gst, $taxClassId, $taxes);
            } else {
                $taxes = [0];
            }
        } elseif ($state_code != $origin_state && $ut_gst != 'NULL') {//if user from Union Territory
        $taxClassId = TaxClass::where('name', 'Union Territory GST')->pluck('id')->toArray(); //Get the class Id  of state
         if ($taxClassId) {
             $taxes = $cartController->getTaxByPriority($taxClassId);
             $value = $cartController->getValueForUnionTerritory($productid, $c_gst, $ut_gst, $taxClassId, $taxes);
         } else {
             $taxes = [0];
         }
        }

        return ['taxes'=>$taxes, 'value'=>$value];
    }

    /**
     *Tax When from other Country.
     */
    public function getTaxWhenOtherCountry($geoip_state, $geoip_country, $productid)
    {
        $cartController = new CartController();
        $taxClassId = Tax::where('state', $geoip_state)->orWhere('country', $geoip_country)->pluck('tax_classes_id')->first();
        $value = '';
        $rate = '';
        if ($taxClassId) { //if state equals the user State

            $taxes = $cartController->getTaxByPriority($taxClassId);

            // $taxes = $this->cartController::getTaxByPriority($taxClassId);
            $value = $cartController->getValueForOthers($productid, $taxClassId, $taxes);
            $rate = $value;
        } else {//if Tax is selected for Any State Any Country
            $taxClassId = Tax::where('country', '')->where('state', 'Any State')->pluck('tax_classes_id')->first();
            if ($taxClassId) {
                $taxes = $cartController->getTaxByPriority($taxClassId);
                $value = $cartController->getValueForOthers($productid, $taxClassId, $taxes);
                $rate = $value;
            } else {
                $taxes = [0];
            }
        }

        return ['taxes'=>$taxes, 'value'=>$value, 'rate'=>$rate];
    }

    /**
     * Get tax when enabled.
     */
    public function getTaxWhenEnable($productid, $taxs, $userid)
    {
        $rate = $this->getRate($productid, $taxs, $userid);
        $taxs = ([$rate['taxs']['0']['name'], $rate['taxs']['0']['rate']]);

        return $taxs;
    }

    /**
     * GeT Total Rate.
     */
    public function getTotalRate($taxClassId, $productid, $taxs)
    {
        $cartController = new CartController();
        $taxs = $cartController->getTaxByPriority($taxClassId);
        $value = $cartController->getValueForOthers($productid, $taxClassId, $taxs);
        if ($value == 0) {
            $status = 0;
        }
        $rate = $value;

        return $rate;
    }

    /**
     * Get Grandtotal.
     **/
    public function getGrandTotal($code, $total, $cost, $productid, $currency)
    {
        if ($code) {
            $grand_total = $this->checkCode($code, $productid, $currency);
        } else {
            if (!$total) {
                $grand_total = $cost;
            } else {
                $grand_total = $total;
            }
        }

        return $grand_total;
    }

    /**
     * Get Message on Invoice Generation.
     **/
    public function getMessage($items, $user_id)
    {
        if ($items) {
            $this->sendmailClientAgent($user_id, $items->invoice_id);
            $result = ['success' => \Lang::get('message.invoice-generated-successfully')];
        } else {
            $result = ['fails' => \Lang::get('message.can-not-generate-invoice')];
        }

        return $result;
    }

    /**
     * get Subtotal.
     */
    public function getSubtotal($user_currency, $cart)
    {
        if ($user_currency == 'INR') {
            $subtotal = \App\Http\Controllers\Front\CartController::rounding($cart->getPriceSumWithConditions());
        } else {
            $subtotal = \App\Http\Controllers\Front\CartController::rounding($cart->getPriceSumWithConditions());
        }

        return $subtotal;
    }

    public function calculateTotal($rate, $total)
    {
        try {
            $rates = explode(',', $rate);

            //            $total = '';
            $rule = new TaxOption();
            $rule = $rule->findOrFail(1);
            if ($rule->inclusive == 0) {
                foreach ($rates as $rate) {
                    if ($rate != '') {
                        $rate = str_replace('%', '', $rate);
                        $total += $total * ($rate / 100);
                    }
                }
            }

            return intval(round($total));
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    public function getExpiryStatus($start, $end, $now)
    {
        //both not set, always true
        if (($start == null || $start == '0000-00-00 00:00:00') && ($end == null || $end == '0000-00-00 00:00:00')) {
            return 'success';
        }
        //only starting date set, check the date is less or equel to today
        if (($start != null || $start != '0000-00-00 00:00:00') && ($end == null || $end == '0000-00-00 00:00:00')) {
            if ($start <= $now) {
                return 'success';
            }
        }
        //only ending date set, check the date is greater or equel to today
        if (($end != null || $end != '0000-00-00 00:00:00') && ($start == null || $start == '0000-00-00 00:00:00')) {
            if ($end >= $now) {
                return 'success';
            }
        }
        //both set
        if (($end != null || $start != '0000-00-00 00:00:00') && ($start != null || $start != '0000-00-00 00:00:00')) {
            if ($end >= $now && $start <= $now) {
                return 'success';
            }
        }
    }

    public function checkCode($code, $productid, $currency)
    {
        try {
            if ($code != '') {
                $promo = $this->promotion->where('code', $code)->first();
                //check promotion code is valid
                if (!$promo) {
                    throw new \Exception(\Lang::get('message.no-such-code'));
                }
                $relation = $promo->relation()->get();
                //check the relation between code and product
                if (count($relation) == 0) {
                    throw new \Exception(\Lang::get('message.no-product-related-to-this-code'));
                }
                //check the usess
                $uses = $this->checkNumberOfUses($code);
                //dd($uses);
                if ($uses != 'success') {
                    throw new \Exception(\Lang::get('message.usage-of-code-completed'));
                }
                //check for the expiry date
                $expiry = $this->checkExpiry($code);
                //dd($expiry);
                if ($expiry != 'success') {
                    throw new \Exception(\Lang::get('message.usage-of-code-expired'));
                }
                $value = $this->findCostAfterDiscount($promo->id, $productid, $currency);

                return $value;
            } else {
                $product = $this->product->find($productid);
                $price = $product->price()->sales_price;
                if (!$price) {
                    $price = $product->price()->price;
                }

                return $price;
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception(\Lang::get('message.check-code-error'));
        }
    }

    public function getPrice($price, $price_model)
    {
        if ($price == '') {
            $price = $price_model->sales_price;
            if (!$price) {
                $price = $price_model->price;
            }
        }

        return $price;
    }
}
