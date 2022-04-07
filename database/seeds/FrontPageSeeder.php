<?php

use Illuminate\Database\Seeder;

class FrontPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('frontend_pages')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        \App\DefaultPage::create([
            'page_id'           => '1',
            'page_url'          => url('/my-invoices'),
        ]);
    }
}
