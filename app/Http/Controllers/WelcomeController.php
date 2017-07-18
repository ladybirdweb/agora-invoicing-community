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
        $country = new Country();
        $country_iso2 = $this->request->get('country_id');
        $model = $country->where('country_code_char2', $country_iso2)->select('phonecode')->first();

        return $model->phonecode;
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

    public function countryCount()
    {
        $users = \App\User::
                leftJoin('countries', 'users.country', '=', 'countries.country_code_char2')
                ->select('countries.nicename as Country', \DB::raw('COUNT(users.id) as count'))
                ->groupBy('users.country')
                ->get()
                ->sortByDesc('count');
        echo '<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>';
        echo '<table>';
        echo '<tr><th>Country</th><th>Count</th><tr>';
        foreach ($users as $user) {
            echo '<tr><td>'.$user->Country.'</td><td>'.$user->count.'</td></tr>';
        }
        echo '</table>';
    }
}
