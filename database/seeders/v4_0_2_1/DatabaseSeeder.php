<?php

namespace Database\Seeders\v4_0_2_1;

use Illuminate\Database\Seeder;
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
        $this->call([
            TemplateTableSeeder::class,
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}




class TemplateTableSeeder extends Seeder
{
    public function run()
    {
    
    Template::where('id',23)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
   <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Renew your subscription to continue using our services.</h1>
  
   </td>
   <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
   </tr>
   <tr>
   <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
   <td style="background: #fff; padding: 0; width: 560px;" align="left">
   <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Your subscription with {{company_title}} is set to expire on {{date}}.</p>
   <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">To ensure uninterrupted service, please complete your payment now. With Auto-renewal enabled, all future payments will be automatic and hassle-free.&nbsp;</p>
   <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Thank you for choosing Ladybird Web Solutions!&nbsp;</p>
   <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
      <thead>
      <tr style="background-color: #f8f8f8;">
      <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
      <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order No</th>
      <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Total</th>

      </tr>
      </thead>
      <tbody>
      <tr>
      <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{product}}</td>
      <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{number}}</td>
      <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{total}}</td>
      </tr>
      </tbody>
      </table>
   <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to make a payment.</p>
   </td>
   <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
   </tr>
   <tr>
   <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
   <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank" rel="noopener"> Make Payment </a><br><br>
      <p style="font-family:sans-serif;font-weight:normal;padding:0;margin:0;font-size:14px;line-height:19px;margin-bottom:10px;color: grey">
         This link will expire on {{expiry_date}}
     </p>
   </td>
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



