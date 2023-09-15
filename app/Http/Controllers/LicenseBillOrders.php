<?php

namespace App\Http\Controllers;
use App\Model\Order\Order;

use Illuminate\Http\Request;

class LicenseBillOrders extends Controller
{
    public function orderid($number){
        dd($number);
        $data= Order::where('number', $number)->first();
        $client =$data->client;
        return redirect("clients/".$data->client);
    }
    }

