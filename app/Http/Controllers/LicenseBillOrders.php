<?php

namespace App\Http\Controllers;

use App\Model\Order\Order;
use Illuminate\Http\Request;
use App\ThirdPartyApp;

class LicenseBillOrders extends Controller
{
    public function orderid(Request $request ,$number)
    {
         $app = ThirdPartyApp::where('app_secret', $request->secret)
        ->first();
        if($request->secret == $app->app_secret){
             $data = Order::where('number', $number)->value('id');
        return $data;            
        }        
    }
}
