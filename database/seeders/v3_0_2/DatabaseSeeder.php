<?php

namespace Database\Seeders\v3_0_2;

use Database\Seeders\SocialLoginsTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\SocialLogin;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('social_logins')->truncate();
         $social_logins = [
            [
                'type' => 'Google',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 0,
            ],
            [
                'type' => 'Github',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 0,
            ],
            [
                'type' => 'Twitter',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 0,
            ],
            [
                'type' => 'Linkedin',
                'client_id' => '',
                'client_secret' => '',
                'redirect_url' => '',
                'status' => 0,
            ],
        ];
       foreach ($social_logins as $data) {
            SocialLogin::insertOrIgnore($data);
        }
    }
}