<?php

namespace App\Traits;

use App\Model\Payment\Tax;

trait TaxCalculation
{
    public function getDetailsWhenUserStateIsIndian($user_state, $origin_state,$productid,$geoip_state, $geoip_country,$status=1)
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
        return ['taxes'=>$taxes , 'status'=>$status , 'value'=>$value,
        'rate'=>$value,'cgst'=>$c_gst,'sgst'=>$s_gst,'igst'=>$i_gst,'utgst'=>$ut_gst,'statecode'=>$state_code];
    }


    public function getDetailsWhenUserFromOtherCountry($user_state, $geoip_state, $geoip_country, $productid, $status=1)
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
        return ['taxes'=>$taxes , 'status'=>  $status , 'value'=>$value , 'rate'=>$rate,'cgst'=>'','sgst'=>'','igst'=>'','utgst'=>'','statecode'=>''];
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
        return ['taxes'=>$taxes , 'value'=>$value,'status'=>$status];
    }
}
