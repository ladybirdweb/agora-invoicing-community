<?php

use App\Model\Payment\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'name'      => 'Helpdesk Advance',
        'allow_tax' => '1',
        'days'      => 365,
    ];
});
