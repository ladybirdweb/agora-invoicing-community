<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function checkDomain(Request $request)
    {
        try {
            $result = 'fails';
            $url = $request->input('url');

            //            if (ends_with($domain, '/')) {
            //                $domain = substr_replace($domain,"", -1, 1);
            //            }

            $url_info = parse_url($url);
            $domain1 = $url_info['host'];
            $url = preg_replace('#^www\.(.+\.)#i', '$1', $url_info['host']); //remove www from domain
            $domain2 = 'www.'.$url;
            $domain1check = $url.','.$domain2;
            $orders = new Order();
            $order = $orders->where('domain', $domain1check)->orWhere('domain', $url)->orWhere('domain', $domain2)->first();
            if ($order) {
                $product = Product::where('id', $order->product)->pluck('name')->first();
                $result = 'success';
            }

            return response()->json(compact('result', 'product'));
        } catch (Exception $ex) {
            $error = $ex->getMessage();

            return response()->json(compact('error'));
        }
    }
}
