<?php

use Faker\Generator as Faker;
use App\Model\Payment\Plan;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name' => 'Helpdesk Advance',
        'allow_tax' => '1',
        'days' => 365, 
    ];
});
