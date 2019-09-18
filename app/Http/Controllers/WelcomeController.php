<?php

namespace App\Http\Controllers;

use App\Model\Common\Country;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getCode()
    {
        $code = '';
        $country = new Country();
        $country_iso2 = $this->request->get('country_id');
        $model = $country->where('country_code_char2', $country_iso2)->select('phonecode')->first();
        if ($model) {
            $code = $model->phonecode;
        }

        return $code;
    }

    public function getCurrency()
    {
        $currency = 'INR';
        $country_iso2 = $this->request->get('country_id');
        if ($country_iso2 != 'IN') {
            $currency = 'USD';
        }

        return $currency;
    }

    public function getCountry()
    {
        return view('themes.default1.common.country-count');
    }

    public function countryCount()
    {
        $users = \App\User::leftJoin('countries', 'users.country', '=', 'countries.country_code_char2')
                ->select('countries.nicename as country', 'countries.country_code_char2 as code', \DB::raw('COUNT(users.id) as count'))
                ->groupBy('users.country')
                ->get()
                ->sortByDesc('count');

        return\ DataTables::of($users)
                            ->addColumn('country', function ($model) {
                                return ucfirst($model->country);
                            })
                              ->addColumn('count', function ($model) {
                                  return '<a href='.url('clients/'.$model->id.'?country='.$model->code).'>'
                            .($model->count).'</a>';
                              })

                            ->rawColumns(['country', 'count'])
                            ->make(true);
    }
}
