<?php

use App\Model\Order\Invoice;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
       'id'      => 1 ,
        'user_id' => 1,
        'number'  => '2344353',
        'grand_total' => 10000,
        'currency'    => 'INR',
        'status'      => 'success',
    ];
});
