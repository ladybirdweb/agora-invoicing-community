<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CartController;
use App\Model\Order\Invoice;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Product\Product;
use App\User;
use Bugsnag;
use Illuminate\Http\Request;

class BaseInvoiceController extends Controller
{
    public function invoiceGenerateByForm(Request $request, $user_id = '')
    {
        $qty = 1;

        try {
            if ($user_id == '') {
                $user_id = \Request::input('user');
            }
            $productid = $request->input('product');
            $code = $request->input('code');
            $total = $request->input('price');
            $plan = $request->input('plan');
            $description = $request->input('description');
            if ($request->has('domain')) {
                $domain = $request->input('domain');
                $this->setDomain($productid, $domain);
            }
            $controller = new \App\Http\Controllers\Front\CartController();
            $currency = $controller->currency($user_id);
            $number = rand(11111111, 99999999);
            $date = \Carbon\Carbon::now();
            $product = Product::find($productid);
            $cost = $controller->cost($productid, $user_id, $plan);
            if ($cost != $total) {
                $grand_total = $total;
            }
            $grand_total = $this->getGrandTotal($code, $total, $cost, $productid, $currency);
            $grand_total = $qty * $grand_total;

            $tax = $this->checkTax($product->id, $user_id);
            $tax_name = '';
            $tax_rate = '';
            if (!empty($tax)) {
                $tax_name = $tax[0];
                $tax_rate = $tax[1];
            }

            $grand_total = $this->calculateTotal($tax_rate, $grand_total);
            $grand_total = \App\Http\Controllers\Front\CartController::rounding($grand_total);

            $invoice = Invoice::create(['user_id' => $user_id, 'number' => $number, 'date' => $date, 'grand_total' => $grand_total, 'currency' => $currency, 'status' => 'pending', 'description' => $description]);

            $items = $this->createInvoiceItemsByAdmin($invoice->id, $productid, $code, $total, $currency, $qty, $plan, $user_id, $tax_name, $tax_rate);
            $result = $this->getMessage($items, $user_id);
        } catch (\Exception $ex) {
            app('log')->useDailyFiles(storage_path().'/laravel.log');
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);
            $result = ['fails' => $ex->getMessage()];
        }

        return response()->json(compact('result'));
    }

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
            $payment = $this->updateInvoicePayment($invoiceid, $payment_method, $payment_status, $payment_date, $amount);

            return redirect()->back()->with('success', 'Payment Accepted Successfully');
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }



    public function setDomain($productid, $domain)
    {
        try {
            if (\Session::has('domain'.$productid)) {
                \Session::forget('domain'.$productid);
            }
            \Session::put('domain'.$productid, $domain);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
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
}
