<?php

namespace Database\Seeders\v2_0_2;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([TemplateTypeTableSeeder::class]);
        $this->command->info('Template Type table seeded!');

        $this->call([TemplateTableSeeder::class]);
        $this->command->info('Template table seeded!');

        $this->call([StatusSettingSeeder::class]);
        $this->command->info('Status Setting table seeded!');

    }

    class StatusSettingSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('status_settings')->truncate();
        StatusSetting::create(['id' => 1, 'subs_expirymail' => 0,'post_expirymail' => 0]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

    class TemplateTypeTableSeeder extends Seeder
   {
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('template_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        TemplateType::create(['id' => 12, 'name' => 'autosubscription_going_to_end']);
    }
  }

  class TemplateTableSeeder extends Seeder
   {
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('templates')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Template::create(['id' => 12, 'name' => '[Faveo Helpdesk] Auto renewal reminder', 'type' => 12, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
</td>
<td style="width: 30px;">&nbsp;</td>
</tr>
<tr>
<td style="width: 30px;">&nbsp;</td>
<td style="width: 640px; padding-top: 30px;">
<table style="width: 640px; border-bottom: 1px solid #cccccc; height: 437.5px;" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 122px;">
<td style="background: #ffffff; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 122px;">&nbsp;</td>
<td style="background: #ffffff; border-top: 1px solid #cccccc; padding: 40px 0px 10px; width: 560px; height: 122px;" align="left">Dear {$name},<br /><br />
<h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your orders are expiring soon.</h1>
</td>
<td style="background: #ffffff; border-right: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 122px;">&nbsp;</td>
</tr>
<tr style="height: 247px;">
<td style="background: #ffffff; border-left: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 247px;">&nbsp;</td>
<td style="background: #ffffff; padding: 0px; width: 560px; height: 247px;" align="left">
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Some of your orders are expiring soon. We are informing you that your credit card will be automatically charged.</p>
<p>&nbsp;</p>
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
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$number}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$product}</td>
<td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry} <br /><span style="color: #ad7b33; font-family: Arial, sans-serif; font-size: 12px;">Expiring Soon!</span></td>
</tr>
</tbody>
</table>
<p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">&nbsp;</p>
</td>
<td style="background: #ffffff; border-right: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 247px;">&nbsp;</td>
</tr>
<tr style="height: 68.5px;">
<td style="background: #ffffff; border-left: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 68.5px;">&nbsp;</td>
<td style="background: #ffffff; padding: 20px 0px 50px; width: 560px; height: 68.5px;" align="left">&nbsp;</td>
<td style="background: #ffffff; border-right: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 68.5px;">&nbsp;</td>
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
<p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br />Tel: +91 80 3075 2618</p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
</td>
<td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
<p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br />Tel: +91 80 3075 2618</p>
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
    }
    
   }
}