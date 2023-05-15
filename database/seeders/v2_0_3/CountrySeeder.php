<?php

namespace Database\Seeders\v2_0_3;


use App\Model\Common\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      Country::where('nicename','Tajikistan')->delete();
       
      }
}