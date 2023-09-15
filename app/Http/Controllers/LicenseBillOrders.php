<?php

namespace App\Http\Controllers;

use App\Model\Order\Order;

class LicenseBillOrders extends Controller
{
    public function orderid($number)
    {
        $data = Order::where('number', $number)->value('id');

        return redirect('orders/'.$data);
    }
}
