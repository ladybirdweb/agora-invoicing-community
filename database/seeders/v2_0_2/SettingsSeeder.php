<?php

namespace Database\Seeders\v2_0_2;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Model\Common\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \App\Model\Common\Setting::create([
            'autosubscription_going_to_end' => 12,
            'payment_successfull' => 13,
            'payment_failed' => 14,

        ]);
    }
}
