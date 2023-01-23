<?php

namespace Database\Seeders;

use App\Model\Common\AnnouncementConditions;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AnnouncementConditions::updateOrCreate(['condition'=> 'Close the message on update','name'=>'Update']);
        AnnouncementConditions::updateOrCreate(['condition'=> 'Close the message on renewal','name'=>'Renew']);
    }
}