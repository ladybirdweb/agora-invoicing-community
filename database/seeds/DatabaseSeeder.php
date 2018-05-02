<?php

use App\Model\Common\Template;
use App\Model\Common\TemplateType;
use App\Model\Github\Github;
use App\Model\Payment\Currency;
use App\Model\Payment\Period;
use App\Model\Payment\Plan;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Payment\TaxOption;
use App\Model\Product\Product;
use App\Model\Product\ProductGroup;
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

        $this->call('PeriodTypeTableSeeder');
        $this->command->info('Period table seeded!');

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

        $this->call('TaxOptionTableSeeder');
        $this->command->info('Tax Option Table seeded');

        $this->call('ProductTableSeeder');
        $this->command->info('Product table seeded!');

        $this->call('GitHubTableSeeder');
        $this->command->info('Github table seeded!');

        $this->call('UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call(CompanySize::class);
        $this->call(CompanyType::class);
        $this->call(SettingsSeeder::class);
        $this->call(FrontPageSeeder::class);

        \DB::unprepared(file_get_contents(storage_path('agora.sql')));
        \DB::unprepared(file_get_contents(storage_path('states.sql')));
        \DB::unprepared(file_get_contents(storage_path('taxrates.sql')));
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
            Plan::create(['name' => $subcriptions[$i]['name'], 'product'=>'1', 'allow_tax'=>'1', 'days' => $subcriptions[$i]['days']]);
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
            Type::create(['id' => $i + 1, 'name' => $types[$i]['name'], 'description'=>'null']);
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
        Currency::create(['id' => 1, 'code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar', 'base_conversion' => '1.0']);
    }
}

class TaxOptionTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('tax_rules')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        TaxOption::create(['id' => 1, 'tax_enable' => 0, 'inclusive' => 0, 'rounding' => 1]);
    }
}

class PeriodTypeTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('periods')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Period::create(['id' => 1, 'name' => '1 Year', 'days' => '365']);
        Period::create(['id' => 2, 'name' => '2 Years', 'days' => '730']);
        Period::create(['id' => 3, 'name' => '3 Years', 'days' => '1095']);
        Period::create(['id' => 4, 'name' => '4 Years', 'days' => '1460']);
        Period::create(['id' => 5, 'name' => '5 Years', 'days' => '1825']);
        Period::create(['id' => 6, 'name' => '6 Years', 'days' => '2190']);
        Period::create(['id' => 7, 'name' => '7 Years', 'days' => '2555']);
        Period::create(['id' => 8, 'name' => '8 Years', 'days' => '2920']);
        Period::create(['id' => 9, 'name' => '9 Years', 'days' => '3285']);
    }
}

class GroupTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('product_groups')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ProductGroup::create(['id' => 1, 'name' => 'none', 'headline' => 'none', 'tagline' => 'none', 'available_payment'=>'null', 'hidden' => 0, 'cart_link' => 'none']);
    }
}

class PromotionTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('promotions')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Promotion::create(['id' => 1, 'code' => 'none', 'type' => 1, 'uses' => 0, 'value' => 'none', 'start' => '1000-01-01 00:00:00', 'expiry' => '1000-01-01 00:00:00']);
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
        // Product::create(['id'=>2, 'name'=>'none1', 'type'=>1, 'group' =>1]);
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
        TemplateType::create(['id' => 4, 'name' => 'subscription_going_to_end']);
        TemplateType::create(['id' => 5, 'name' => 'subscription_over']);
        TemplateType::create(['id' => 6, 'name' => 'invoice']);
        TemplateType::create(['id' => 7, 'name' => 'order_mail']);
        TemplateType::create(['id' => 8, 'name' => 'download_mail']);
        TemplateType::create(['id' => 9, 'name' => 'manager_email']);
    }
}

class TemplateTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('templates')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Template::create(['id' => 2, 'name' => '[Faveo Helpdesk] Verify your email address', 'type' => 1, 'url'=>'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
<p>Dear {{name}},&nbsp;<br /><br /> Before you can login, you must active your account. Click <a href="{{url}}">{{url}}</a> to activate your account.<br /><br /> <strong>Your Profile &amp; Control Panel Login</strong><br /><br /> You can start exploring our feature-rich Control Panel, which will allow you to manage all your Products, buy new Products, check all your transactions and more.<br /><br /> <strong>Login Details:</strong><br /> <strong>URL: </strong><a href="http://faveohelpdesk.com/billing/">www.faveohelpdesk.com</a> <br /> <strong>Username:</strong> {{username}}<br /> <strong>Password:</strong> If you can not recall your current password, <a href="http://faveohelpdesk.com/billing/public/password/email">click here</a> to request a new password to login.<br /><br /> Thank You.<br /> Regards,<br /> Faveo Helpdesk</p>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 0; width: 560px;" align="left">&nbsp;</td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank">Verify Email </a></td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@faveohelpdesk.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">support@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 4, 'name' => '[Faveo Helpdesk] Purchase confirmation', 'type' => 7, 'url'=>'null', 'data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px                      0 0 0;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid                      #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
<h1 style="color: #0088cc; font-family: Arial,sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Thanks for your {{product}} order</h1>
<br /> Your order and payment details are below.</td>
<td style="background: #fff; border-right: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0                      5px 0 0;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 0; width: 560px;" align="left">
<table style="margin: 25px 0 30px                        0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr style="background-color: #f8f8f8;">
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order Number</th>
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Download</th>
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{{number}}</td>
<td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{{product}}</td>
<td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{downloadurl}}" target="_blank"> Download </a></td>
<td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{{expiry}}</td>
</tr>
</tbody>
</table>
<p style="color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click below to login to your Control Panel to view invoice or to pay for any pending invoice.</p>
</td>
<td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px                      0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{invoiceurl}}" target="_blank"> View Invoice </a></td>
<td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@ladybirdweb.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /> <a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">suppport@ladybirdweb.com</a><br /> <a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 5, 'name' => '[Faveo Helpdesk] Reset your password', 'type' => 2, 'url'=>'null', 'data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
<p>Dear {{name}},<br /><br /> A request to reset password was received from your account.&nbsp; Use the link below to reset your password and login.<br /><br /> <strong>Link:</strong>&nbsp; <a href="{{url}}">{{url}}</a><br /><br /> Thank You.<br /> Regards,<br /> Faveo Helpdesk<br /><br /> <strong>IMP:</strong> If you have not initiated this request, <a href="http://www.faveohelpdesk.com/contact-us/">report it to us immediately</a>.<br /><br /> <em>This is an automated email, please do not reply.</em></p>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank">Reset Password </a></td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@faveohelpdesk.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">support@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 6, 'name' => '[Faveo Helpdesk] Consolidated renewal reminder', 'type' => 4, 'url'=>'null', 'data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
<h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your orders are expiring soon.<br /> Renew them now.</h1>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 0; width: 560px;" align="left">
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Some of your orders are expiring soon (or have already expired.) Please renew them before they are deleted to avoid loss of data.</p>
<table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr style="background-color: #f8f8f8;">
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order ID</th>
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry Date</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{number}}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{product}}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{expiry}} <br /><span style="color: #ad7b33; font-family: Arial, sans-serif; font-size: 12px;">Expiring Soon!</span></td>
</tr>
</tbody>
</table>
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank"> Renew Order </a></td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@faveohelpdesk.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">support@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 7, 'name' => '[Faveo Helpdesk] URGENT: Order has expired', 'type' => 5, 'url'=>'null', 'data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
<h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your orders has expired.<br /> Renew them now.</h1>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 0; width: 560px;" align="left">
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Some of your orders are expired. Please renew them before they are deleted to avoid loss of data.</p>
<table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr style="background-color: #f8f8f8;">
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order ID</th>
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
<th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry Date</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{number}}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{product}}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{expiry}}</td>
</tr>
</tbody>
</table>
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
</td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank"> Renew Order </a></td>
<td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@faveohelpdesk.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">suppport@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 8, 'name' => '[Faveo Helpdesk] Invoice', 'type' => 6, 'url'=>'null', 'data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="http://www.faveohelpdesk.com/billing/public/cart/img/logo/faveo.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px                      0 0 0;">&nbsp;</td>
<td style="background: #fff; border-top: 1px solid                      #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
<h1 style="color: #0088cc; font-family: Arial,sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Thanks for your order</h1>
<br /> Your order and payment details are below.</td>
<td style="background: #fff; border-right: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0                      5px 0 0;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 0; width: 560px;" align="left">
<table style="margin: 25px 0 30px                        0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
<thead>
<tr style="background-color: #f8f8f8;">
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Invoice Number</th>
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
<th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Total</th>
</tr>
</thead>
<tbody>
<tr>
<td>&nbsp;{{content}}</td>
</tr>
</tbody>
</table>
<p style="color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click below to login to your Control Panel to view invoice or to pay for any pending invoice.</p>
</td>
<td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
<tr>
<td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="background: #fff; padding: 20px 0 50px                      0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{invoiceurl}}" target="_blank"> Invoice </a></td>
<td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
<td style="padding: 20px 0 10px 0; width: 640px;" align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
</tr>
<tr>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:info@faveohelpdesk.com">info@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="http://www.ladybirdweb.com/support">http://www.ladybirdweb.com/support</a></p>
</td>
<td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /> <a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">support@ladybirdweb.com</a><br /> <a class="moz-txt-link-freetext" href="http://www.faveohelpdesk.com">http://www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
</td>
</tr>
</tbody>
</table>
</td>
<td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>']);

        Template::create(['id' => 9, 'name' => '[Faveo Helpdesk] Your New Account Manager', 'type' => 9, 'url'=>'null', 'data' =>'<p>Dear {{name}},</p>
<p>This is {{manager_first_name}} {{manager_last_name}}.</p>
<p>From now onwards I will be your one point of contact. I will followup with you as well as with our team. Please feel free to get in touch with me anytime if you have any issues with regards to your account. You can also add me on Skype. My ID is mentioned in my signature. It is a pleasure to have you on board and I look forward to effective conversations with you in future.</p>
<p>Hope you have a great day.</p>
<p>Regards,</p>
<p>{{manager_first_name}}{{manager_last_name}}</p>
<p>Account Manager,<br /> Faveo Helpdesk<br /> Mobile :{{manager_code}} {{manager_mobile}}<br /> Skype ID : {{manager_skype}}<br /> Email : {{manager_email}}</p>']);
    }
}
class GitHubTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('githubs')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Github::create(['id' => 1, 'client_id'=>'', 'client_secret'=>'', 'username'=>'', 'password'=>'']);
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('users')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return \App\User::create([
                    'first_name'      => 'Demo',
                    'last_name'       => 'Admin',
                    'user_name'       => 'demo',
                    'email'           => 'demo@admin.com',
                    'role'            => 'admin',
                    'password'        => \Hash::make('password'),
                    'active'          => 1,
                    'mobile_verified' => 1,
                    'currency'        => 'INR',
                    'company'         => 'ladybird',
                    'mobile'          => '',
                    'mobile_code'     => '',
                    'address'         => '',
                    'town'            => '',
                    'state'           => '',
                    'zip'             => '',
                    'profile_pic'     => '',
                    'debit'           => 0,
        ]);
    }
}
