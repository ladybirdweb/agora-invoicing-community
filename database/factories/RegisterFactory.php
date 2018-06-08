<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'user_name'            => $faker->userName,
       'first_name'            => $faker->firstName,
       'last_name'             => $faker->lastName,
       'email'                 => $faker->unique()->safeEmail,
       'company'               => $faker->company,
       'bussiness'             => 'abcd',
       'company_type'          => 'public_company',
       'company_size'          => '2-50',
       'country'               => $faker->country,
       'mobile'                => $faker->e164PhoneNumber,
       'address'               => $faker->address,
       'city'                  => $faker->city,
       'state'                 => $faker->state,
       'zip'                   => $faker->postcode,
       'ip'                    => $faker->ipv4,
       'password'              => bcrypt('password'),
       'password_confirmation' => bcrypt('password'),
       'remember_token'        => str_random(10),
        'mobile_verified'      => 1,
        'active'               => 1,
        'role'                 => 'user',
    ];
});
