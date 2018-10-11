<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;

class AdvanceSearchController extends Controller
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
}
