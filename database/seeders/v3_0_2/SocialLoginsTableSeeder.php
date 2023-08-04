<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialLoginsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $social_logins = [
            [
                'type' => 'google',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 1,
            ],
            [
                'type' => 'github',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 1,
            ],
            [
                'type' => 'twitter',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 1,
            ],
            [
                'type' => 'LinkedIn',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 1,
            ],
        ];
       \DB::table('social_logins')->insert($social_logins);
    }
    
}
