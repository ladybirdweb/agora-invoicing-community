<?php

namespace App\Traits;

use App\Model\Order\Invoice;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\Promotion;
use App\Model\Product\Product;
use Bugsnag;
use Illuminate\Http\Request;

//////////////////////////////////////////////////////////////////////////////
// ADVANCE SEARCH FOR INVOICE AND COUPON CODE CALCULATION
//////////////////////////////////////////////////////////////////////////////

trait CoupCodeAndInvoiceSearch
{
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
                $value = $this->findCostAfterDiscount($promo->id, $productid, $currency);

                return $value;
            } else {
                $product = $this->product->find($productid);
                $plans = Plan::where('product', $product)->pluck('id')->first();
                $price = PlanPrice::where('currency', $currency)->where('plan_id', $plans)->pluck('add_price')->first();

                return $price;
            }
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function findCostAfterDiscount($promoid, $productid, $currency)
    {
        try {
            $promotion = Promotion::findOrFail($promoid);
            $product = Product::findOrFail($productid);
            $promotion_type = $promotion->type;
            $promotion_value = $promotion->value;
            $planId = Plan::where('product', $productid)->pluck('id')->first();
            // dd($planId);
            $product_price = PlanPrice::where('plan_id', $planId)
            ->where('currency', $currency)->pluck('add_price')->first();
            $updated_price = $this->findCost($promotion_type, $promotion_value, $product_price, $productid);

            return $updated_price;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception(\Lang::get('message.find-discount-error'));
        }
    }

    public function findCost($type, $value, $price, $productid)
    {
        $price = intval($price);
        switch ($type) {
            case 1:
                $percentage = $price * ($value / 100);

                return $price - $percentage;
            case 2:
                return $price - $value;
            case 3:
                return $value;
            case 4:
                return 0;
        }
    }

    public function advanceSearch($name = '', $invoice_no = '', $currency = '', $status = '', $from = '', $till = '')
    {
        $join = Invoice::leftJoin('users', 'invoices.user_id', '=', 'users.id');
        $this->name($name, $join);
        $this->invoice_no($invoice_no, $join);
        $this->status($status, $join);
        $this->searchcurrency($currency, $join);

        $this->invoice_from($from, $till, $join);
        $this->till_date($till, $from, $join);

        $join = $join->select('id', 'user_id', 'number', 'date', 'grand_total', 'currency', 'status', 'created_at');

        $join = $join->orderBy('created_at', 'desc')
        ->select(
            'invoices.id',
            'first_name',
            'invoices.created_at',
            'invoices.currency',
            'user_id',
            'invoices.grand_total',
            'number',
            'status'
        );

        return $join;
    }

    public function name($name, $join)
    {
        if ($name) {
            $join = $join->where('first_name', $name);

            return $join;
        }
    }

    public function invoice_no($invoice_no, $join)
    {
        if ($invoice_no) {
            $join = $join->where('number', $invoice_no);

            return $join;
        }
    }

    public function status($status, $join)
    {
        if ($status) {
            $join = $join->where('status', $status);

            return $join;
        }
    }

    public function searchcurrency($currency, $join)
    {
        if ($currency) {
            $join = $join->where('invoices.currency', $currency);

            return $join;
        }
        $return;
    }

    public function invoice_from($from, $till, $join)
    {
        if ($from) {
            $fromdate = date_create($from);
            $from = date_format($fromdate, 'Y-m-d H:m:i');
            $tills = date('Y-m-d H:m:i');
            $tillDate = $this->getTillDate($from, $till, $tills);
            $join = $join->whereBetween('invoices.created_at', [$from, $tillDate]);

            return $join;
        }

        return $join;
    }

    public function till_date($till, $from, $join)
    {
        if ($till) {
            $tilldate = date_create($till);
            $till = date_format($tilldate, 'Y-m-d H:m:i');
            $froms = Invoice::first()->created_at;
            $fromDate = $this->getFromDate($from, $froms);
            $join = $join->whereBetween('invoices.created_at', [$fromDate, $till]);

            return $join;
        }
    }

    public function getExpiryStatus($start, $end, $now)
    {
        $whenDateNotSet = $this->whenDateNotSet($start, $end);
        if ($whenDateNotSet) {
            return $whenDateNotSet;
        }
        $whenStartDateSet = $this->whenStartDateSet($start, $end, $now);
        if ($whenStartDateSet) {
            return $whenStartDateSet;
        }
        $whenEndDateSet = $this->whenEndDateSet($start, $end, $now);
        if ($whenEndDateSet) {
            return $whenEndDateSet;
        }
        $whenBothAreSet = $this->whenBothSet($start, $end, $now);
        if ($whenBothAreSet) {
            return $whenBothAreSet;
        }
    }

    public function getTillDate($from, $till, $tills)
    {
        if ($till) {
            $todate = date_create($till);
            $tills = date_format($todate, 'Y-m-d H:m:i');
        }

        return $tills;
    }

    public function getFromDate($from, $froms)
    {
        if ($from) {
            $fromdate = date_create($from);
            $froms = date_format($fromdate, 'Y-m-d H:m:i');
        }

        return $froms;
    }

    public function updateInvoicePayment($invoiceid, $payment_method, $payment_status, $payment_date, $amount)
    {
        try {
            $invoice = Invoice::find($invoiceid);
            $invoice_status = 'pending';

            $payment = $this->payment->create([
                'invoice_id'     => $invoiceid,
                'user_id'        => $invoice->user_id,
                'amount'         => $amount,
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,
                'created_at'     => $payment_date,
            ]);
            $all_payments = $this->payment
            ->where('invoice_id', $invoiceid)
            ->where('payment_status', 'success')
            ->pluck('amount')->toArray();
            $total_paid = array_sum($all_payments);
            if ($total_paid >= $invoice->grand_total) {
                $invoice_status = 'success';
            }
            if ($invoice) {
                $invoice->status = $invoice_status;
                $invoice->save();
            }

            return $payment;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $invoice = $this->invoice->where('id', $id)->first();
                    if ($invoice) {
                        $invoice->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function deletePayment(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $payment = $this->payment->where('id', $id)->first();
                    if ($payment) {
                        $invoice = $this->invoice->find($payment->invoice_id);
                        if ($invoice) {
                            $invoice->status = 'pending';
                            $invoice->save();
                        }
                        $payment->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!</b> 
                    './* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */ \Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }
}
