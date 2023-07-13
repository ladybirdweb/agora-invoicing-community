<?php

namespace Database\Seeders\v3_0_3;

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
        \App\Model\Common\Setting::where('id',1)->update([
            'cloud_created' => 20
        ]);
    }
}