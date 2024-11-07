<?php

namespace Database\Seeders\v4_0_2_3;

use App\FileSystemSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->fileSystemSeeder();
    }
    public function fileSystemSeeder()
    {
        FileSystemSettings::firstOrCreate([
            'disk' => 'system',
        ]);
    }
}
