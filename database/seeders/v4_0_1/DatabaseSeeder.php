<?php

namespace Database\Seeders\v4_0_1;
use Illuminate\Database\Seeder;
use App\Model\Common\PricingTemplate;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call([PricingTemplateSeeder::class]);
        $this->command->info('Pricing Template Table Seeded!');


    }

}

class PricingTemplateSeeder extends Seeder
{
    public function run()
    {

        PricingTemplate::whereIn('id', [1, 2])->update(['image' => 'pricing_template1.png','name' => 'Porto Theme(With Gap Style)']);
        
    }
}