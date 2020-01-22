<?php

namespace App\Http\Controllers\User;

use App\Model\Order\Payment;
use App\User;
use Illuminate\Http\Request;

class AdvanceSearchController extends AdminOrderInvoiceController
{
    /**
     * Serach for Mobile,Email,Country.
     */
    public function getMobEmCoun($join, $mobile, $email, $country)
    {
        if ($mobile) {
            $join = $join->where('mobile', $mobile);
        }
        if ($email) {
            $join = $join->where('email', 'LIKE', '%'.$email.'%');
        }
        if ($country) {
            $join = $join->where('country', $country);
        }

        return $join;
    }

    /**
     * Serach for industry,company_type,company_size.
     */
    public function getInCtCs($join, $industry, $company_type, $company_size)
    {
        if ($industry) {
            $join = $join->where('bussiness', $industry);
        }
        if ($company_type) {
            $join = $join->where('company_type', $company_type);
        }
        if ($company_size) {
            $join = $join->where('company_size', $company_size);
        }

        return $join;
    }

    /**
     * Serach for Role,Position.
     */
    public function getRolPos($join, $role, $position)
    {
        if ($role) {
            $join = $join->where('role', $role);
        }
        if ($position) {
            $join = $join->where('position', $position);
        }

        return $join;
    }

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

    /**
     * Serach for Name,UserName,Company.
     */
    public function getNamUserCom($join, $name, $username, $company)
    {
        if ($name) {
            $join = $join->where('first_name', 'LIKE', '%'.$name.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$name.'%');
        }
        if ($username) {
            $join = $join->where('user_name', 'LIKE', '%'.$username.'%');
        }
        if ($company) {
            $join = $join->where('company', 'LIKE', '%'.$company.'%');
        }

        return $join;
    }

    /*
     * Search for users having a sales/acct manager 
     */
    
    public function getSalesAcctManager($join, $acc_manager, $sales_manager)
    {
        if($acc_manager) {
            $join = $join->where('account_manager', $acc_manager);
        }

         if($sales_manager) {
            $join = $join->where('manager', $sales_manager);
        }
        
        return $join;
    }

    public function advanceSearch(
        $name = '',
        $username = '',
        $company = '',
        $mobile = '',
        $email = '',
        $country = '',
        $industry = '',
        $company_type = '',
        $company_size = '',
        $role = '',
        $position = '',
        $reg_from = '',
        $reg_till = '',
        $acc_manager = '',
        $sales_manager
    ) {
        $join = \DB::table('users');
        $join = $this->getNamUserCom($join, $name, $username, $company);
        $join = $this->getMobEmCoun($join, $mobile, $email, $country);
        $join = $this->getInCtCs($join, $industry, $company_type, $company_size);
        $join = $this->getRolPos($join, $role, $position);
        $join = $this->getregFromTill($join, $reg_from, $reg_till);
        $join = $this->getSalesAcctManager($join, $acc_manager, $sales_manager);

        $join = $join->orderBy('created_at', 'desc')
        ->select(
            'id',
            'first_name',
            'last_name',
            'email',
            'created_at',
            'active',
            'mobile_verified',
            'role',
            'position'
        );

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
