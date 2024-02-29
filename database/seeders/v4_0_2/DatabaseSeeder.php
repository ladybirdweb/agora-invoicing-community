<?php

namespace Database\Seeders\v4_0_2;

use Illuminate\Database\Seeder;
use App\Model\Product\ProductUpload;
use App\ReleaseType;
use App\Model\License\LicenseType;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ReleaseType::truncate();
        LicenseType::where('id',7)->delete();

        $this->call([
            ReleaseTypeSeeder::class,
            LicenseTypeSeeder::class
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}



class ReleaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReleaseType::create(['id' => 1, 'type' => 'Pre Release','value' => '1']);
        ReleaseType::create(['id' => 2, 'type' => 'Latest Release','value' => '0']);
    }
}

class LicenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LicenseType::create(['id' => 7, 'name' => 'Development License']);
    }
}



