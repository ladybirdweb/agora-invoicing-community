<?php

use App\Model\Product\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [

        'name'                => 'Helpdesk Advance',
        'description'         => 'Few Lines Of Description',
       // 'type'                => 1,
        'category'            => 'helpdesk',
        'group'               => 1,
        'can_modify_agent'    => 0,
        'can_modify_quantity' => 0,
        'require_domain'      => 1,
        'show_agent'          => 1,
        'github_owner'        => 'ladybirdweb',
        'github_repository'   => 'faveo-helpdesk-advance',
    ];
});
