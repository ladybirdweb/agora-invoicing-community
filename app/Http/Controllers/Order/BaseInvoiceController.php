<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Front\CartController;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Payment\Promotion;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\User;
use Bugsnag;
use Illuminate\Http\Request;

class BaseInvoiceController extends ExtendedBaseInvoiceController
{
    /**
     *Tax When state is not empty.
     */
    public function getTaxWhenState($user_state, $productid, $origin_state)
    {
        $taxes = [];
        $value = [];
        $cartController = new CartController();
        $c_gst = $user_state->c_gst;
        $s_gst = $user_state->s_gst;
        $i_gst = $user_state->i_gst;
        $ut_gst = $user_state->ut_gst;
        $state_code = $user_state->state_code;
        if ($state_code == $origin_state) {//If user and origin state are same
            $taxClassId = TaxClass::where('name', 'Intra State GST')
             ->pluck('id')->toArray(); //Get the class Id  of state
            if ($taxClassId) {
                $taxes = $cartController->getTaxByPriority($taxClassId);
                $value = $cartController->getValueForSameState($productid, $c_gst, $s_gst, $taxClassId, $taxes);
            } else {
                $taxes = [0];
            }
        } elseif ($state_code != $origin_state && $ut_gst == 'NULL') {//If user is from other state
            $taxClassId = TaxClass::where('name', 'Inter State GST')
            ->pluck('id')->toArray(); //Get the class Id  of state
            if ($taxClassId) {
                $taxes = $cartController->getTaxByPriority($taxClassId);
                $value = $cartController->getValueForOtherState($productid, $i_gst, $taxClassId, $taxes);
            } else {
                $taxes = [0];
            }
        } elseif ($state_code != $origin_state && $ut_gst != 'NULL') {//if user from Union Territory
            $taxClassId = TaxClass::where('name', 'Union Territory GST')
            ->pluck('id')->toArray(); //Get the class Id  of state
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
        $taxClassId = Tax::where('state', $geoip_state)
        ->orWhere('country', $geoip_country)
        ->pluck('tax_classes_id')->first();
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

    public function whenDateNotSet($start, $end)
    {
        //both not set, always true
        if (($start == null || $start == '0000-00-00 00:00:00') &&
         ($end == null || $end == '0000-00-00 00:00:00')) {
            return 'success';
        }
    }

    public function whenStartDateSet($start, $end, $now)
    {
        //only starting date set, check the date is less or equel to today
        if (($start != null || $start != '0000-00-00 00:00:00')
         && ($end == null || $end == '0000-00-00 00:00:00')) {
            if ($start <= $now) {
                return 'success';
            }
        }
    }

    public function whenEndDateSet($start, $end, $now)
    {
        //only ending date set, check the date is greater or equel to today
        if (($end != null || $end != '0000-00-00 00:00:00') && ($start == null || $start == '0000-00-00 00:00:00')) {
            if ($end >= $now) {
                return 'success';
            }
        }
    }

    public function whenBothSet($start, $end, $now)
    {
        //both set
        if (($end != null || $start != '0000-00-00 00:00:00') && ($start != null || $start != '0000-00-00 00:00:00')) {
            if ($end >= $now && $start <= $now) {
                return 'success';
            }
        }
    }

    public function postPayment($invoiceid, Request $request)
    {
        $this->validate($request, [
            'payment_method' => 'required',
            'amount'         => 'required|numeric',
            'payment_date'   => 'required|date_format:Y-m-d',
        ]);

        try {
            $payment_method = $request->input('payment_method');
            $payment_status = 'success';
            $payment_date = $request->input('payment_date');
            $amount = $request->input('amount');
            $payment = $this->updateInvoicePayment(
                $invoiceid,
                $payment_method,
                $payment_status,
                $payment_date,
                $amount
            );

            return redirect()->back()->with('success', 'Payment Accepted Successfully');
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function domain($id)
    {
        try {
            if (\Session::has('domain'.$id)) {
                $domain = \Session::get('domain'.$id);
            } else {
                $domain = '';
            }

            return $domain;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
        }
    }

    public function checkExpiry($code = '')
    {
        try {
            if ($code != '') {
                $promotion = Promotion::where('code', $code)->first();

                $start = $promotion->start;
                $end = $promotion->expiry;
                $now = \Carbon\Carbon::now();
                $getExpiryStatus = $this->getExpiryStatus($start, $end, $now);

                return $getExpiryStatus;
            }
        } catch (\Exception $ex) {
            throw new \Exception(\Lang::get('message.check-expiry'));
        }
    }

    /*
    *Edit Invoice Total.
    */
    public function invoiceTotalChange(Request $request)
    {
        $total = $request->input('total');
        if ($total == '') {
            $total = 0;
        }
        $number = $request->input('number');
        $invoiceId = Invoice::where('number', $number)->value('id');
        $invoiceItem = InvoiceItem::where('invoice_id', $invoiceId)->update(['subtotal'=>$total]);
        $invoices = Invoice::where('number', $number)->update(['grand_total'=>$total]);
    }

    public function getCodeValue($promo, $code)
    {
        if ($promo && $code) {
            return $promo->value;
        }
    }

    /**
     * Check if Session has Code and Value of Code.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-02-22T13:10:50+0530
     *
     * @return array
     */
    protected function getCodeFromSession()
    {
        $code = '';
        $codevalue = '';
        if (\Session::has('code')) {//If coupon code is applied get it here from Session
            $code = \Session::get('code');
            $codevalue = \Session::get('codevalue');
        }

        return ['code'=> $code, 'codevalue'=>$codevalue];
    }
}
