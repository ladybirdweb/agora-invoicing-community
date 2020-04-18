<?php

namespace App\Http\Controllers\User;

use App\Model\Order\Payment;
use App\User;
use Illuminate\Http\Request;

class AdvanceSearchController extends AdminOrderInvoiceController
{

    /**
     * Serach for Registered From,tILL.
     */
    public function getregFromTill($join, $reg_from, $reg_till)
    {
        if ($reg_from) {
            $fromdate = date_create($reg_from);

            $from = date_format($fromdate, 'Y-m-d H:m:i');
            $tills = date('Y-m-d H:m:i');
            $cont = new \App\Http\Controllers\Order\ExtendedOrderController();
            $tillDate = $cont->getTillDate($from, $reg_till, $tills);
            $join = $join->whereBetween('created_at', [$from, $tillDate]);
        }
        if ($reg_till) {
            $tilldate = date_create($reg_till);
            $till = date_format($tilldate, 'Y-m-d H:m:i');
            $froms = User::first()->created_at;
            $cont = new \App\Http\Controllers\Order\ExtendedOrderController();
            $fromDate = $cont->getFromDate($reg_from, $froms);
            $join = $join->whereBetween('created_at', [$fromDate, $till]);
        }

        return $join;
    }

    public function search(Request $request)
    {
        try {
            $term = trim($request->q);
            if (empty($term)) {
                return \Response::json([]);
            }
            $users = User::where('email', 'LIKE', '%'.$term.'%')
             ->orWhere('first_name', 'LIKE', '%'.$term.'%')
             ->orWhere('last_name', 'LIKE', '%'.$term.'%')
             ->select('id', 'email', 'profile_pic', 'first_name', 'last_name')->get();
            $formatted_tags = [];

            foreach ($users as $user) {
                $formatted_users[] = ['id'     => $user->id, 'text' => $user->email, 'profile_pic' => $user->profile_pic,
                'first_name'                   => $user->first_name, 'last_name' => $user->last_name, ];
            }

            return \Response::json($formatted_users);
        } catch (\Exception $e) {
            // returns if try fails with exception meaagse
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function getUsers(Request $request)
    {
        $options = $this->user
                ->select('email AS text', 'id AS value')
                ->get();

        return response()->json(compact('options'));
    }

    public function getClientDetail($id)
    {
        $client = $this->user->where('id', $id)->first();
        $currency = $client->currency;
        if (array_key_exists('name', \App\Http\Controllers\Front\CartController::getStateByCode($client->state))) {
            $client->state = \App\Http\Controllers\Front\CartController::getStateByCode($client->state)['name'];
        }
        $client->country = ucwords(strtolower(\App\Http\Controllers\Front\CartController::getCountryByCode($client->country)));

        $displayData = (['currency'=>$currency, 'client'=> $client]);

        return $displayData;
    }

    public function getExtraAmt($userId)
    {
        try {
            $amounts = Payment::where('user_id', $userId)->where('invoice_id', 0)->select('amt_to_credit')->get();
            $balance = 0;
            foreach ($amounts as $amount) {
                if ($amount) {
                    $balance = $balance + $amount->amt_to_credit;
                }
            }

            return $balance;
        } catch (\Exception $ex) {
            app('log')->info($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
