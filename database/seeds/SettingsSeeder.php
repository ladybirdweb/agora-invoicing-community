<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('settings')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \App\Model\Common\Setting::create([
            'company'                   => 'Ladybird Web Solution',
            'city'                      => 'Bangalore',
            'state'                     => 'Karnataka',
            'country'                   => 'India',
            'website'                   => 'http://www.ladybirdweb.com',
            'error_log'                 => 1,
            'invoice'                   => 8,
            'subscription_over'         => 7,
            'subscription_going_to_end' => 6,
            'forgot_password'           => 5,
            'order_mail'                => 4,
            'welcome_mail'              => 2,
            'download'                  => 9,
            'invoice_template'          => 8,
            // 'phone'                     => '',
            // 'address'                   => '',
            // 'driver'                    => '',
            // 'host'                      => '',
            // 'port'                      => 1,
            // 'encryption'                => '',
            // 'email'                     => '', 'password'=>'', 'error_log'=>0, 'error_email'=>0,
            // 'invoice'                   => 0, 'download'=>0, 'subscription_over'=>0, 'subscription_going_to_end'=>0, 'forgot_password'=>0, 'order_mail'=>0, 'welcome_mail'=>1, 'invoice_template'=>0, 'driver'=>'',
        ]);
    }
}
