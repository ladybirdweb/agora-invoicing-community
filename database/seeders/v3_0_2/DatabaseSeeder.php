<?php

namespace Database\Seeders\v3_0_2;
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
        $this->call(StatesSubdivisionSeeder::class);	        
        $this->call(CountrySeeder::class);	       

        $this->call([TemplateTypeTableSeeder::class]);
        $this->command->info('Template Type table seeded!');

        $this->call([TemplateTableSeeder::class]);
        $this->command->info('Template table seeded!');

        $this->call(SettingsSeeder::class);
    }	    
}	
    
    class TemplateTypeTableSeeder extends Seeder
   {
    public function run()
    {

        TemplateType::create(['id' => 16, 'name' => 'Free_trail_expired']);
        TemplateType::create(['id' => 17, 'name' => 'Free_trail_gonna_expired']);


    }
  }

   class SettingsSeeder extends Seeder
   {
    public function run()
    {

        SettingsSeeder::create(['id' => 16, 'name' => 'Free_trail_expired']);
        SettingsSeeder::create(['id' => 17, 'name' => 'Free_trail_gonna_expired']);

    }
  }

    class TemplateTableSeeder extends Seeder
   {
     public function run()
    {

          Template::create(['id' => 16, 'name' => '[Faveo Cloud] Free Trail has Expired', 'type' => 16, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                                <table style="width: 640px;border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
                                <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Faveo Cloud - Free Trail has Expired</h1>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 0; width: 560px;" align="left">
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">We regret to inform you that your trial product has expired and your access to it has been suspended. We hope you had a positive experience using our product during your trial period and we appreciate your interest in our services.</p></br>
                                <p>If you wish to continue using our product, we encourage you to renew your subscription as soon as possible. Without renewal, your Cloud Instance will be deleted and you will no longer have access to it.</p></br>
                                <p>To renew your subscription please use the below link. Without renewal, your Cloud instance will be deleted and you will no longer have access to it.</p></br>
                                <p>Thankyou For Choosing Faveo!</p>
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
                                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry}</td>
                                </tr>
                                </tbody>
                                </table>
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="'.$url.'" target="_blank"> Renew Order </a></td>
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
                                <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
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




          Template::create(['id' => 17, 'name' => '[Faveo Cloud] Free Trail Gonna Expire', 'type' => 17, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                                <table style="width: 640px;border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
                                <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Free Trial Cloud Product is Expiring in 7 Days!</h1>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 0; width: 560px;" align="left">
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">We hope this email finds you well. We wanted to remind you that your free trial for our cloud product is coming to an end in just 7 days. We appreciate your interest in our services and would like to offer you the opportunity to continue enjoying the benefits of our product by upgrading to the paid version.</p></br>
                              
                                <p>To renew your subscription please use the below link.</p></br>
                                <p>Thankyou For Choosing Faveo!</p>
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
                                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry}</td>
                                </tr>
                                </tbody>
                                </table>
                                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
                                </td>
                                <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                                <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="'.$url.'" target="_blank"> Renew Order </a></td>
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
                                <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
                                </td>
                                <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                                <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
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

