<?php

namespace Database\Seeders\v3_0_2;

use Database\Seeders\SocialLoginsTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SocialLoginsTableSeeder::class);
    }
}