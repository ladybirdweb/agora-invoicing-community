<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Exception;
use App\Model\Order\Order;

class ApiController extends Controller
{
    public function checkDomain(Request $request){
        try{
            $result = "fails";
            $url = $request->input('url');

//            if (ends_with($domain, '/')) {
//                $domain = substr_replace($domain,"", -1, 1);
//            }
            
            $url_info = parse_url($url);
            $domain = $url_info['host'];
            
            $orders = new Order();
            $order = $orders->where('domain',$domain)->first();
            if($order){
               $result = "success"; 
            }
            return response()->json(compact('result'));
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            return response()->json(compact('error'));
        }
    }
}
