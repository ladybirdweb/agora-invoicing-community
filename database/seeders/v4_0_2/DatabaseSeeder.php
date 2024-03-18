<?php

namespace Database\Seeders\v4_0_2;

use Illuminate\Database\Seeder;
use App\Model\Product\ProductUpload;
use App\ReleaseType;
use App\Model\License\LicenseType;
use App\Model\Common\TemplateType;
use App\Model\Common\Template;

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
            LicenseTypeSeeder::class,
            TemplateTypeTableSeeder::class,
            TemplateTableSeeder::class,
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

class TemplateTypeTableSeeder extends Seeder
{
    public function run()
    {
        TemplateType::where('id',23)->delete();
        TemplateType::create(['id' => 23, 'name' => 'stripe_subscription_authentication']);
    }
}


class TemplateTableSeeder extends Seeder
{
    public function run()
    {
        Template::where('id',23)->delete();
        Template::create(['id' => 23, 'name' => 'Start your subscription', 'type' => 23, 'url' => 'null', 'reply_to' => '{{email}}','data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td style="width: 640px; padding-top: 30px;">
        <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{{logo}}</h2>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td style="width: 640px; padding-top: 30px;">
        <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
        <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Start your subscription - {{product}} </h1><br>
        <p style="font-family:sans-serif;font-weight:normal;padding:0;margin:0;font-size:14px;line-height:19px;margin-bottom:10px;text-align:center;color: grey">
            This link will expire on {{expiry_date}}
        </p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="background: #fff; padding: 0; width: 560px;" align="left">
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Your subscription with {{application_title}} - {{company_title}} has been created. </p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Please complete your first transaction by clicking on the button below and entering your payment details to enable automatic payments for future transactions..&nbsp;</p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;"><strong>SUBSCRIPTION PLAN</strong></p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">{{product}}</p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;"><strong>AMOUNT TO PAY</strong></p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">{{total}}</p>
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to make a payment.</p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank" rel="noopener"> Make Payment </a></td>
        <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="padding: 20px 0 10px 0; width: 640px;" align="left">{{contact}}</td>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>']);

    }
}



