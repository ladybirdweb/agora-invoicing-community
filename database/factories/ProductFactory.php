<?php

use App\Model\Product\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [

        'name' => $faker->name,
        'description' => $faker->sentence,
        // 'type'                => 1,
        'group' => 1,
        'can_modify_agent' => 0,
        'can_modify_quantity' => 0,
        'require_domain' => 1,
        'show_agent' => 1,
        'product_sku' => 'FAVEOCLOUDBETA',
    ];
});
