<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

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
