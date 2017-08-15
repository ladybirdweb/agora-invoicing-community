<?php

use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\Github\Github;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Product\Product;
use App\Model\Product\ProductGroup;
use App\Model\Product\Subscription;
use App\Model\Product\Type;
use Illuminate\Database\Eloquent\Model;
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
        //Model::unguard();

        $this->call('PlanTableSeeder');
        $this->command->info('Plan table seeded!');

        $this->call('TemplateTypeTableSeeder');
        $this->command->info('Template Type table seeded!');

        $this->call('TemplateTableSeeder');
        $this->command->info('Template table seeded!');

        $this->call('GroupTableSeeder');
        $this->command->info('Product Group table seeded!');

        $this->call('ProductTypesTableSeeder');
        $this->command->info('Product Types table seeded!');

        $this->call('PromotionTypeTableSeeder');
        $this->command->info('Promotion Types table seeded!');

        $this->call('PromotionTableSeeder');
        $this->command->info('Promotion table seeded!');

        $this->call('CurrencyTableSeeder');
        $this->command->info('Currency table seeded!');

        $this->call('ProductTableSeeder');
        $this->command->info('Product table seeded!');

        $this->call('GitHubTableSeeder');
        $this->command->info('Github table seeded!');

        $this->call(CompanySize::class);
        $this->call(CompanyType::class);
        $this->call(SettingsSeeder::class);
        $this->call(FrontPageSeeder::class);

        \DB::unprepared(file_get_contents(storage_path('agora.sql')));
        \DB::unprepared(file_get_contents(storage_path('states.sql')));
    }
}

class PlanTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('plans')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $subcriptions = [
            0 => ['name' => 'no subcription', 'days' => 0],
            1 => ['name' => 'one week', 'days' => 7],
            2 => ['name' => 'one month', 'days' => 30],
            3 => ['name' => 'three months', 'days' => 90],
            4 => ['name' => 'six months', 'days' => 180],
            5 => ['name' => 'one year', 'days' => 365],
            6 => ['name' => 'three year', 'days' => 1095],
            ];
        //var_dump($subcriptions);
        for ($i = 0; $i < count($subcriptions); $i++) {
            Plan::create(['id' => $i + 1, 'name' => $subcriptions[$i]['name'], 'days' => $subcriptions[$i]['days']]);
        }
    }
}

class ProductTypesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('product_types')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $types = [
            1 => ['name' => 'download'],
            0 => ['name' => 'SaaS'],
            ];
        //var_dump($subcriptions);
        for ($i = 0; $i < count($types); $i++) {
            Type::create(['id' => $i + 1, 'name' => $types[$i]['name']]);
        }
    }
}
class CurrencyTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('currencies')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Currency::create(['id' => 1, 'code' => 'USD', 'symbol' => '$', 'name' => 'US Doller', 'base_conversion' => '1.0']);
    }
}

class GroupTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('product_groups')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ProductGroup::create(['id' => 1, 'name' => 'none', 'headline' => 'none', 'tagline' => 'none', 'hidden' => 0, 'cart_link' => 'none']);
    }
}

class PromotionTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('promotions')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Promotion::create(['id' => 1, 'code' => 'none', 'type' => 1, 'uses' => 0, 'value' => 'none', 'start' => '', 'expiry' => '']);
    }
}

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('products')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Product::create(['id' => 1, 'name' => 'default', 'type' => 1, 'group' => 1]);
        //Product::create(['id'=>2,'name'=>'none1','type'=>1,'group' =>1]);
    }
}

class PromotionTypeTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('promotion_types')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        PromotionType::create(['id' => 1, 'name' => 'Percentage']);
        PromotionType::create(['id' => 2, 'name' => 'Fixed Amount']);
        PromotionType::create(['id' => 3, 'name' => 'Price Override']);
        PromotionType::create(['id' => 4, 'name' => 'Free Setup']);
    }
}

class TemplateTypeTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('template_types')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        TemplateType::create(['id' => 1, 'name' => 'welcome_mail']);
        TemplateType::create(['id' => 2, 'name' => 'forgot_password']);
        TemplateType::create(['id' => 3, 'name' => 'order_mail']);
        TemplateType::create(['id' => 4, 'name' => 'subscription_going_to_end']);
        TemplateType::create(['id' => 5, 'name' => 'subscription_over']);
        TemplateType::create(['id' => 6, 'name' => 'invoice']);
        TemplateType::create(['id' => 7, 'name' => 'cart']);
        TemplateType::create(['id' => 8, 'name' => 'manager_email']);
    }
}

class TemplateTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('templates')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Template::create(['id' => 1, 'name' => 'cart1', 'type' => 3, 'data' => '<div class="col-sm-6 col-md-3 col-lg-3"><div class="pricing-item"><div class="pricing-item-inner"><div class="pricing-wrap"><div class="pricing-icon"><i class="fa fa-credit-card-alt"></i></div><div class="pricing-title">{{title}}</div><div class="pricing-features font-alt"><ul class="sf-list pr-list"><li>{{feature}}</li></ul></div><div class="pricing-num"><sup>{{currency}}</sup>{{price}}</div><div class="pr-per">{{subscription}}</div><div class="pr-button"><a href="{{url}}" class="btn btn-mod">Buy Now</a></div></div> </div> </div></div>']);
    }
}
class GitHubTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('githubs')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Github::create(['id' => 1]);
    }
}
