<?php

namespace App\Plugins\Ping\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plugins\Ping\Model\Ping;
use Schema;
use Pingpp\Pingpp;

class ProcessController extends Controller {

    public function __construct() {
        $apikey = 'sk_test_ibbTe5jLGCi5rzfH4OqPW9KC';
        \Pingpp\Pingpp::setApiKey($apikey);
    }

    public function Process($event) {

        $order = $event['order'];
        $extra = array(
            'success_url' => 'http://ladybirdweb.com/support/',
            'cancel_url' => 'http://ladybirdweb.com/support/'
        );
        $ch = \Pingpp\Charge::create(
                        array(
                            'order_no' => $order->id,
                            'app' => array('id' => 'app_1Gqj58ynP0mHeX1q'),
                            'channel' => 'alipay_wap',
                            'amount' => $order->price,
                            'client_ip' => '127.0.0.1',
                            'currency' => 'cny',
                            'subject' => 'Your Subject',
                            'body' => 'Your Body',
                            'extra' => $extra
                        )
        );
        dd($ch);
       
    }

}
