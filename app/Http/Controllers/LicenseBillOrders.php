<?php

namespace App\Http\Controllers;

use App\Model\Order\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LicenseBillOrders extends Controller
{
    public function orderid($number)
    {
        //dd($number);
        //'order_number' = "10000005",
        $data = Order::where('number', $number)->first();

        //$client =$data->client;
        return redirect('clients/'.$data->client);
        //dd("dfghjk");

        //$response = Http::withoutVerifying()->post('https://gurmeen.localhost/lakshya/public/orderget2',);
        $response = Http::withoutVerifying()->get('https://gurmeen.localhost/lakshya/public/orderget2', [
            'name' => $data,
            'role' => 'Network Administrator',
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            // Parse the JSON response
            $data = $response->json();

            // Now you can use the $data in Project 2
        } else {
            dd('fail');
            // Handle the error if the request was not successful
        }
        //return response()->json($data);
    }
}
