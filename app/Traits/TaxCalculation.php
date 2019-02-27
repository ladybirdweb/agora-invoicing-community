<?php

namespace App\Traits;

use App\Model\Payment\Tax;
use App\Model\Payment\TaxProductRelation;
use Cart;
use Illuminate\Http\Request;
use Session;

trait TaxCalculation
{
    public function __construct($s_gst = '', $c_gst = '', $state_code = '', $ut_gst = '', $i_gst = '')
    {
        $this->c_gst = $c_gst;
        $this->s_gst = $s_gst;
        $this->state_code = $state_code;
        $this->ut_gst = $ut_gst;
        $this->i_gst = $i_gst;
    }

    public function getDetailsWhenUserStateIsIndian($user_state, $origin_state, $productid, $geoip_state, $geoip_country, $status = 1)
    {
        if ($user_state != '') {//Get the CGST,SGST,IGST,STATE_CODE of the user,if user from INdia
            $c_gst = $user_state->c_gst;
            $s_gst = $user_state->s_gst;
            $i_gst = $user_state->i_gst;
            $ut_gst = $user_state->ut_gst;
            $state_code = $user_state->state_code;

            if ($state_code == $origin_state) {//If user and origin state are same
                $rateForSameState = $this->getTaxWhenIndianSameState($user_state, $origin_state, $productid, $c_gst, $s_gst, $state_code, $status);
                $taxes = $rateForSameState['taxes'];
                $status = $rateForSameState['status'];
                $value = $rateForSameState['value'];
            } elseif ($state_code != $origin_state && $ut_gst == 'NULL') {//If user is from other state
                $rateForOtherState = $this->getTaxWhenIndianOtherState($user_state, $origin_state, $productid, $i_gst, $state_code, $status);
                $taxes = $rateForOtherState['taxes'];
                $status = $rateForOtherState['status'];
                $value = $rateForOtherState['value'];
            } elseif ($state_code != $origin_state && $ut_gst != 'NULL') {//if user from Union Territory
                $rateForUnionTerritory = $this->getTaxWhenUnionTerritory($user_state, $origin_state, $productid, $c_gst, $ut_gst, $state_code, $status);
                $taxes = $rateForUnionTerritory['taxes'];
                $status = $rateForUnionTerritory['status'];
                $value = $rateForUnionTerritory['value'];
            }
        } else {
            $details = $this->getDetailsWhenUserFromOtherCountry($user_state, $geoip_state, $geoip_country, $productid, $status);

            return $details;
        }

        return ['taxes'=> $taxes, 'status'=>$status, 'value'=>$value,
        'rate'         => $value, 'cgst'=>$c_gst, 'sgst'=>$s_gst, 'igst'=>$i_gst, 'utgst'=>$ut_gst, 'statecode'=>$state_code, ];
    }

    public function getDetailsWhenUserFromOtherCountry($user_state, $geoip_state, $geoip_country, $productid, $status = 1)
    {
        $taxClassId = Tax::where('state', $geoip_state)
                ->orWhere('country', $geoip_country)
                ->pluck('tax_classes_id')->first();
        if ($taxClassId) { //if state equals the user State or country equals user country
            $taxForSpecificCountry = $this->getTaxForSpecificCountry($taxClassId, $productid, $status);
            $taxes = $taxForSpecificCountry['taxes'];
            $status = $taxForSpecificCountry['status'];
            $value = $taxForSpecificCountry['value'];
            $rate = $taxForSpecificCountry['value'];
        } else {//if Tax is selected for Any Country Any State
            $taxClassId = Tax::where('country', '')
                    ->where('state', 'Any State')
                    ->pluck('tax_classes_id')->first();
            if ($taxClassId) {
                $taxForAnyCountry = $this->getTaxForAnyCountry($taxClassId, $productid, $status);
                $taxes = $taxForAnyCountry['taxes'];
                $status = $taxForAnyCountry['status'];
                $value = $taxForAnyCountry['value'];
                $rate = $taxForAnyCountry['value'];
            } else {
                $value = '';
                $rate = '';
                $taxes = [0];
            }
        }

        return ['taxes'=>$taxes, 'status'=>  $status, 'value'=>$value, 'rate'=>$rate, 'cgst'=>'', 'sgst'=>'', 'igst'=>'', 'utgst'=>'', 'statecode'=>''];
    }

    public function whenOtherTaxAvailableAndTaxNotEnable($taxClassId, $productid)
    {
        $status = 1;
        $taxClassId = Tax::where('country', '')
            ->where('state', 'Any State')
            ->pluck('tax_classes_id')->first(); //In case of India when
        // other tax is available and tax is not enabled
        if ($taxClassId) {
            $taxes = $this->getTaxByPriority($taxClassId);
            $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
            if ($value == 0) {
                $status = 0;
            }
            $rate = $value;
        } else {//In case of other country
            //when tax is available and tax is not enabled
            //(Applicable when Global Tax class for any country and state is not there)
            $taxClassId = Tax::where('state', $geoip_state)
            ->orWhere('country', $geoip_country)
            ->pluck('tax_classes_id')->first();
            if ($taxClassId) { //if state equals the user State
                $taxes = $this->getTaxByPriority($taxClassId);
                $value = $this->getValueForOthers($productid, $taxClassId, $taxes);
                if ($value == '') {
                    $status = 0;
                }
                $rate = $value;
            }
        }

        return ['taxes'=>$taxes, 'value'=>$value, 'status'=>$status];
    }

    /**
     *   Get tax value for Same State.
     *
     * @param int  $productid
     * @param type $c_gst
     * @param type $s_gst
     *                        return type
     */
    public function getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes)
    {
        try {
            $value = '';
            $value = $taxes->toArray()[0]['active'] ?

                  (TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassId)->count() ?
                        $c_gst + $s_gst.'%' : 0) : 0;

            return $value;
        } catch (Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     *   Get tax value for Other States.
     *
     * @param type $productid
     * @param type $i_gst
     *                        return type
     */
    public function getValueForOtherState($productid, $i_gst, $taxClassId, $taxes)
    {
        $value = '';
        $value = $taxes->toArray()[0]['active'] ? //If the Current Class is active
              (TaxProductRelation::where('product_id', $productid)->where('tax_class_id', $taxClassId)->count() ?
                        $i_gst.'%' : 0) : 0; //IGST

        return $value;
    }

    /**
     *  Get tax value for Union Territory.
     *
     * @param type $productid
     * @param type $c_gst
     * @param type $ut_gst
     *                        return type
     */
    public function getValueForUnionTerritory($productid, $c_gst, $ut_gst, $taxClassId, $taxes)
    {
        $value = '';
        $value = $taxes->toArray()[0]['active'] ?
             (TaxProductRelation::where('product_id', $productid)
              ->where('tax_class_id', $taxClassId)->count() ? $ut_gst + $c_gst.'%' : 0) : 0;

        return $value;
    }

    public function otherRate($productid)
    {
        $otherRate = '';

        return $otherRate;
    }

    public function getValueForOthers($productid, $taxClassId, $taxes)
    {
        $otherRate = 0;
        $status = $taxes->toArray()[0]['active'];
        if ($status && (TaxProductRelation::where('product_id', $productid)
          ->where('tax_class_id', $taxClassId)->count() > 0)) {
            $otherRate = Tax::where('tax_classes_id', $taxClassId)->first()->rate;
        }
        $value = $otherRate.'%';

        return $value;
    }

    /**
     * @param type $tax_class_id
     *
     * @throws \Exception
     *
     * @return type
     */
    public function getTaxByPriority($taxClassId)
    {
        try {
            $taxe_relation = Tax::where('tax_classes_id', $taxClassId)->get();

            return $taxe_relation;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception('error in get tax priority');
        }
    }

    public function cartRemove(Request $request)
    {
        $id = $request->input('id');
        Cart::remove($id);

        return 'success';
    }

    /**
     * @param type $price
     *
     * @throws \Exception
     *
     * @return type
     */
    public static function rounding($price)
    {
        try {
            $tax_rule = new \App\Model\Payment\TaxOption();
            $rule = $tax_rule->findOrFail(1);
            $rounding = $rule->rounding;

            return $price;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            // throw new \Exception('error in get tax priority');
        }
    }

    /**
     * @param type $rate
     * @param type $price
     *
     * @return type
     */
    public static function taxValue($rate, $price)
    {
        try {
            $result = 0;
            if ($rate) {
                $rate = str_replace('%', '', $rate);
                $tax = intval($price) * (intval($rate) / 100);
                $result = $tax;

                $result = self::rounding($result);
            }

            return $result;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function checkCurrencySession()
    {
        try {
            if (Session::has('currency')) {
                return true;
            }

            return false;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }
}
