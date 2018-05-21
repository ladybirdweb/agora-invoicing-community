<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
       'user_name' => $faker->userName,
       'first_name' => $faker->firstName,
       'last_name' => $faker->lastName,
       'email' => $faker->unique()->safeEmail,
       'password' => bcrypt('password'),
       'remember_token' => str_random(10),
        'mobile_verified'=> 1,
        'active'   => 1,
        'role'=>'user',
    ];
});
