<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SocialLogin;

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

        foreach ($social_logins as $login) {
            SocialLogin::updateOrCreate(
                ['type' => $login['type']],
                $login
            );
        }
    }
}
