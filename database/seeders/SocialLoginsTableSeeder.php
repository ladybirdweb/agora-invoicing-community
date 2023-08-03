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
                'type' => 'Google',
                'client_id' => '915644360529-pufkmq6kgrcj0aqe4e6i23tjvb02a5mg.apps.googleusercontent.com',
                'client_secret' => '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',
                'redirect_url' => 'https://qa.faveodemo.com/Agora/agoraBilling/agoraBilling/public/auth/callback/google',
                'status' => 1,
            ],
            [
                'type' => 'Github',
                'client_id' => 'Iv1.f2bdc32a0799a0a0',
                'client_secret' => '92957c0954dc3cd476c855c0ecc3fac69e5f8196',
                'redirect_url' => 'https://qa.faveodemo.com/Agora/agoraBilling/agoraBilling/public/auth/callback/github',
                'status' => 1,
            ],
            [
                'type' => 'Twitter',
                'client_id' => 'OWp4LXE5YXpyZzF4dlByVXpZeXM6MTpjaQ',
                'client_secret' => 'BBCrvmGyASMa355Gfu5aYurxbd0kPnkDjqrFd3omXIMMJN8LtE',
                'redirect_url' => 'https://qa.faveodemo.com/Agora/agoraBilling/agoraBilling/public/auth/redirect/twitter',
                'status' => 0,
            ],
            [
                'type' => 'LinkedIn',
                'client_id' => 'linkedin_client_id',
                'client_secret' => 'linkedin_client_secret',
                'redirect_url' => 'linkedin_redirect_url',
                'status' => 0,
            ],
        ];
        DB::table('social_logins')->insert($social_logins);
    }
    
}
