<?php

use App\Model\Order\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {
    return [
         'number'     => '2344353',
        'grand_total' => 10000,
        'currency'    => 'INR',
        'status'      => 'success',
    ];
});
