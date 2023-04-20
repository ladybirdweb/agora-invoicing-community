<?php

namespace Database\Seeders\v2_0_2;


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
        $this->call(StatesSubdivisionSeeder::class);
    }
}
