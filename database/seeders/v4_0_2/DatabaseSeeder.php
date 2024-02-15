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
        
        ProductUpload::where('release_type_id',2)->delete();
        ReleaseType::truncate();
        LicenseType::where('id',7)->delete();

        $this->call([
            ReleaseTypeSeeder::class,
            ProductUploadSeeder::class,
            LicenseTypeSeeder::class
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

class ProductUploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductUpload::whereNull('release_type_id')->update(['release_type_id' => 2]);
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
        ReleaseType::create(['id' => 1, 'type' => 'Pre release']);
        ReleaseType::create(['id' => 2, 'type' => 'Latest release']);
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



