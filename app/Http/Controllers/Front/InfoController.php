<?php

namespace App\Http\Controllers\Front;

use App\Model\Common\State;
use DateTime;
use DateTimeZone;

class InfoController extends CheckoutController
{
    /**
     * Get User State.
     *
     * @return type
     */
    public function getState()
    {
        if (\Auth::user()->country != 'IN') {
            $states = State::where('state_subdivision_code', \Auth::user()->state)
            ->pluck('state_subdivision_name')->first();
        } else {
            $states = TaxByState::where('state_code', \Auth::user()->state)->pluck('state')->first();
        }

        return $states;
    }

    /**
     * Get The Date.
     */
    public function getDate($invoice)
    {
        $date1 = new DateTime($invoice->date);
        $date1->setTimezone(new DateTimeZone(\Auth::user()->timezone()->first()->name));
        $date = $date1->format('M j, Y, g:i a ');

        return $date;
    }

    /**
     * Wheather appicable for payment.
     *
     * @param type $invoiceid
     */
    public function getPaynow($invoiceid)
    {
        $paynow = false;
        if ($invoiceid) {
            $paynow = true;
        }

        return $paynow;
    }

    public function payment($payment_method, $status)
    {
        if (!$payment_method) {
            $payment_method = 'free';
            $status = 'success';
        }

        return ['payment'=>$payment_method, 'status'=>$status];
    }
}
