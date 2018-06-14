<?php

use App\Model\Order\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
       'number'         => 1123244,
       'order_status'   => 'executed',
       'serial_key'     => 'QWWERRR32434544',
       'domain'         => 'faveo.com',
       'price_override' => 12234443,
       'qty'            => 1,
    ];
});
