<?php

namespace Database\Seeders\v3_0_3;
use Illuminate\Database\Seeder;
use App\Model\Common\TemplateType;
use App\Model\Common\StatusSetting;
use App\Model\Common\Template;
use App\Demo_page;
use Illuminate\Support\Facades\DB;
use App\SocialLogin;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([Demo_pageTableSeeder::class]);

        $this->call([TemplateTypeTableSeeder::class]);
        $this->command->info('Template Type table seeded!');

        $this->call([TemplateTableSeeder::class]);
        $this->command->info('Template table seeded!');
        $this->SocialLoginSeeder();
    }

    private function SocialLoginSeeder()
    {
        DB::table('social_logins')->truncate();
        $social_logins = [
           [
               'type' => 'Google',
               'client_id' => '',
               'client_secret' => '',
               'redirect_url' => '',
               'status' => 0,
           ],
           [
               'type' => 'Github',
               'client_id' => '',
               'client_secret' => '',
               'redirect_url' => '',
               'status' => 0,
           ],
           [
               'type' => 'Twitter',
               'client_id' => '',
               'client_secret' => '',
               'redirect_url' => '',
               'status' => 0,
           ],
           [
               'type' => 'Linkedin',
               'client_id' => '',
               'client_secret' => '',
               'redirect_url' => '',
               'status' => 0,
           ],
       ];
      foreach ($social_logins as $data) {
           SocialLogin::insertOrIgnore($data);
       }
   }
}

class Demo_pageTableSeeder extends Seeder
{
    public function run()
    {

        Demo_page::create(['id' => 1, 'status' => 0]);
    }
}

class TemplateTypeTableSeeder extends Seeder
{
    public function run()
    {

        TemplateType::where('id',12)->update(['name' => 'auto_subscription_going_to_end']);
        TemplateType::create(['id' => 20, 'name' => 'cloud_created']);
    }
}

class TemplateTableSeeder extends Seeder
{
    public function run()
    {

         Template::where('id',2)->update(['name' => 'Verify your email address', 'type' => 1, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
          </td>
          <td style="width: 30px;">&nbsp;</td>
          </tr>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <table style="width: 640px; border-bottom: 1px solid #cccccc; height: 519px;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr style="height: 432px;">
          <td style="background: #ffffff; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 39.6875px; padding-top: 10px; padding-bottom: 10px; height: 432px;">&nbsp;</td>
          <td style="background: #ffffff; border-top: 1px solid #cccccc; padding: 40px 0px 10px; width: 556.602px; height: 432px;" align="left">
          <p>Dear {$name},&nbsp;<br /><br />Before you can login, you must activate your account. Click <a href="{$url}">{$url}</a> to activate your account.<br /><br /><strong>Your Profile &amp; Control Panel Login</strong><br /><br />You can start exploring our feature-rich Control Panel, which will allow you to manage all your Products, buy new Products, check all your transactions and more.<br /><br /><strong>Login Details:</strong><br /><strong>URL: </strong><a href="{$website_url}">{$website_url}</a> <br /><strong>Username:</strong> {$username}<br /><br /><strong>Password:</strong> If you can not recall your current password, <a href="{$website_url}/password/reset">click here</a> to request a new password to log in.<br /><br />Thank You.</p>
          </td>
          <td style="background: #ffffff; border-right: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 39.7109px; padding-top: 10px; padding-bottom: 10px; height: 432px;">&nbsp;</td>
          </tr>
          <tr style="height: 18.5px;">
          <td style="background: #ffffff; border-left: 1px solid #cccccc; width: 39.6875px; padding-top: 10px; padding-bottom: 10px; height: 18.5px;">&nbsp;</td>
          <td style="background: #ffffff; padding: 0px; width: 556.602px; height: 18.5px;" align="left">&nbsp;</td>
          <td style="background: #ffffff; border-right: 1px solid #cccccc; width: 39.7109px; padding-top: 10px; padding-bottom: 10px; height: 18.5px;">&nbsp;</td>
          </tr>
          <tr style="height: 68.5px;">
          <td style="background: #ffffff; border-left: 1px solid #cccccc; width: 39.6875px; padding-top: 10px; padding-bottom: 10px; height: 68.5px;">&nbsp;</td>
          <td style="background: #ffffff; padding: 20px 0px 50px; width: 556.602px; height: 68.5px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$url}" target="_blank" rel="noopener">Verify Email </a></td>
          <td style="background: #ffffff; border-right: 1px solid #cccccc; width: 39.7109px; padding-top: 10px; padding-bottom: 10px; height: 68.5px;">&nbsp;</td>
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
          <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
          </tr>
          <tr>
            {$contact}
          </tr>
          </tbody>
          </table>
          </td>
          <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          </tr>
          </tbody>
          </table>
          <p>&nbsp;</p>']);

        Template::where('id',4)->update(['name' => 'Purchase confirmation', 'type' => 7, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
          </td>
          <td style="width: 30px;">&nbsp;</td>
          </tr>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td style="background: #fff; border-left: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px                      0 0 0;">&nbsp;</td>
          <td style="background: #fff; border-top: 1px solid                      #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
          <h1 style="color: #0088cc; font-family: Arial,sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Thanks for your {$product} order</h1>
          <br />Your order and payment details are below.</td>
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
          <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry</th>
          <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">{$orderHeading}</th>

          </tr>
          </thead>
          <tbody>
          <tr>
          <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{$number}</td>
          <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{$product}</td>
          <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top">{$expiry}</td>
          <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px                              8px;" valign="top"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$downloadurl}" target="_blank" rel="noopener">{$orderHeading}</a></td>
          </tr>
          </tbody>
          </table>
          <p><a class="moz-txt-link-abbreviated" href="{$serialkeyurl}" target="_blank" rel="noopener"> Click Here</a> to get your License Code.</p>
          <p><a class="moz-txt-link-abbreviated" href="{$knowledge_base$}/category-list/installation-and-upgrade-guide"> Refer To Our Knowledge Base</a> for further installation assistance</p>
          <p>Click below to login to your Control Panel to view the invoice or to pay for any pending invoice.</p>
          </td>
          <td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          </tr>
          <tr>
          <td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          <td style="background: #fff; padding: 20px 0 50px                      0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$invoiceurl}" target="_blank" rel="noopener"> View Invoice </a></td>
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
          <td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
          </tr>
          <tr>
         {$contact}
          </tr>
          </tbody>
          </table>
          </td>
          <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          </tr>
          </tbody>
          </table>
          <p>&nbsp;</p>']);


     Template::where('id',5)->update(['name' => 'Reset your password', 'type' => 2, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
          </td>
          <td style="width: 30px;">&nbsp;</td>
          </tr>
          <tr>
          <td style="width: 30px;">&nbsp;</td>
          <td style="width: 640px; padding-top: 30px;">
          <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
          <tr>
          <td style="background: #ffffff; border-left: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 39.7031px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          <td style="background: #ffffff; border-top: 1px solid #cccccc; padding: 40px 0px 10px; width: 556.578px;" align="left">
          <p>Dear {$name},<br /><br />A request to reset your password was received from your account. Use the link below to reset your password and login.<br /><br /><strong>Link:</strong>&nbsp; <a href="{$url}">{$url}</a><br /><br />Thank You.<br /><br /><strong>IMP:</strong> If you have not initiated this request, <a href="{$contact-us}/contact-us/">report it to us immediately</a>.<br /><br /><em>This is an automated email, please do not reply.</em></p>
          </td>
          <td style="background: #ffffff; border-right: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 39.7188px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          </tr>
          <tr>
          <td style="background: #ffffff; border-left: 1px solid #cccccc; width: 39.7031px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          <td style="background: #ffffff; padding: 20px 0px 50px; width: 556.578px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$url}" target="_blank" rel="noopener">Reset Password </a></td>
          <td style="background: #ffffff; border-right: 1px solid #cccccc; width: 39.7188px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
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
          <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
          </tr>
          <tr>
         {$contact}
          </tr>
          </tbody>
          </table>
          </td>
          <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
          </tr>
          </tbody>
          </table>
          <p>&nbsp;</p>']);

         Template::where('id',6)->update(['name' => 'Consolidated renewal reminder', 'type' => 4, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td style="width: 28px;">&nbsp;</td>
    <td style="width: 640px; padding-top: 30px;">
    <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
    </td>
    <td style="width: 28px;">&nbsp;</td>
    </tr>
    <tr>
    <td style="width: 28px;">&nbsp;</td>
    <td style="width: 640px; padding-top: 30px;">
    <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
    <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your orders are expiring soon.<br />Renew them now.</h1>
    </td>
    <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    </tr>
    <tr>
    <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    <td style="background: #fff; padding: 0; width: 560px;" align="left">
    <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Some of your orders are expiring soon.Please renew them before they are deleted to avoid loss of data.</p>
    <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
    <thead>
    <tr style="background-color: #f8f8f8;">
    <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order No</th>
    <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
    <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry Date</th>
    <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">{$product_type}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
    <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$number}</td>
    <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$product}</td>
    <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry} <br /><span style="color: #ad7b33; font-family: Arial, sans-serif; font-size: 12px;">Expiring Soon!</span></td>
    <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$deletionDate}</td>
    </tr>
    </tbody>
    </table>
    <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the below button to login to your Control Panel and renew your orders.</p>
    </td>
    <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    </tr>
    <tr>
    <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$url}" target="_blank" rel="noopener"> Renew Order </a></td>
    <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    </td>
    <td style="width: 28px;">&nbsp;</td>
    </tr>
    <tr>
    <td style="width: 28px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
    <td style="padding: 20px 0 10px 0; width: 640px;" align="left">&nbsp; &nbsp; &nbsp; {$contact}
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
    </tr>
    <tr></tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>']); 


    Template::where('id',7)->update(['name' => 'URGENT: Order has expired', 'type' => 5, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="width: 30px;">&nbsp;</td>
      <td style="width: 640px; padding-top: 30px;">
      <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
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
      <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
      <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your orders has expired.<br />Renew them now.</h1>
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
      <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">{$product_type}</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$number}</td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$product}</td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry}</td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$deletionDate}</td>
      </tr>
      </tbody>
      </table>
      <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
      </td>
      <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      <tr>
      <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$url}" target="_blank" rel="noopener"> Renew Order </a></td>
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
      <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
      </tr>
      <tr>
        {$contact}
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

       Template::where('id',8)->update(['name' => 'Invoice', 'type' => 6, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="width: 30px;">&nbsp;</td>
      <td style="width: 640px; padding-top: 30px;">
      <h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
      </td>
      <td style="width: 30px;">&nbsp;</td>
      </tr>
      <tr>
      <td style="width: 30px;">&nbsp;</td>
      <td style="width: 640px; padding-top: 30px;">
      <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="background: #fff; border-left: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px                      0 0 0;">&nbsp;</td>
      <td style="background: #fff; border-top: 1px solid                      #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
      <h1 style="color: #0088cc; font-family: Arial,sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Thanks for your order</h1>
      <br />Your order and payment details are below.</td>
      <td style="background: #fff; border-right: 1px solid                      #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0                      5px 0 0;">&nbsp;</td>
      </tr>
      <tr>
      <td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      <td style="background: #fff; padding: 0; width: 560px;" align="left">
      <table style="margin: 25px 0px 30px; width: 560px; border: 1px solid #cccccc; height: 38.5px;" border="0" cellspacing="0" cellpadding="0">
      <thead>
      <tr style="background-color: #f8f8f8; height: 20px;">
      <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; height: 20px;" align="left" valign="top">Invoice Number</th>
      <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; height: 20px;" align="left" valign="top">Product</th>
      <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; height: 20px;" align="left" valign="top">Total</th>
      </tr>
      </thead>
      <tbody>
      <tr style="height: 18.5px;">
      <td style="height: 18.5px;">&nbsp;{$content}</td>
      </tr>
      </tbody>
      </table>
      <p style="color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click below to login to your Control Panel to view the invoice or to pay for any pending invoice.</p>
      </td>
      <td style="background: #fff; border-right: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      <tr>
      <td style="background: #fff; border-left: 1px solid                      #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      <td style="background: #fff; padding: 20px 0 50px                      0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{$invoiceurl}" target="_blank" rel="noopener"> Invoice </a></td>
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
      <td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
      </tr>
      <tr>
     {$contact}
      </tr>
      </tbody>
      </table>
      </td>
      <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      </tbody>
      </table>
      <p>&nbsp;</p>']); 


       Template::where('id',9)->update(['name' => 'Your New Sales Manager', 'type' => 9, 'url' => 'null', 'data' => '<p>Dear {$name},</p>
        <p>This is {$manager_first_name} {$manager_last_name}.</p>
        <p>From now onwards I will be your one point of contact. I will follow up with you as well as with our team. Please feel free to get in touch with me anytime if you have any issues with regard to your account. You can also add me on Skype. My ID is mentioned in my signature. It is a pleasure to have you on board and I look forward to effective conversations with you in future.</p>
        <p>Hope you have a great day.</p>
        <p>Regards,</p>
        <p>{$manager_first_name} {$manager_last_name}</p>
        <p>Sales Manager,<br />Mobile :{$manager_code} {$manager_mobile}<br />Skype ID : {$manager_skype}<br />Email : {$manager_email}</p>']); 


         Template::where('id',10)->update(['name' => 'Your New Sales Manager', 'type' => 10, 'url' => 'null', 'data' => '<p>Dear {$name},</p>
        <p>This is {$manager_first_name} {$manager_last_name}.</p>
        <p>From now onwards I will be your one point of contact. I will follow up with you as well as with our team. Please feel free to get in touch with me anytime if you have any issues with regard to your account. You can also add me on Skype. My ID is mentioned in my signature. It is a pleasure to have you on board and I look forward to effective conversations with you in future.</p>
        <p>Hope you have a great day.</p>
        <p>Regards,</p>
        <p>{$manager_first_name} {$manager_last_name}</p>
        <p>Account Manager,<br />Mobile :{$manager_code} {$manager_mobile}<br />Skype ID : {$manager_skype}<br />Email : {$manager_email}</p>']); 


         Template::where('id',12)->update(['name' => 'Consolidated renewal reminder', 'type' => 12, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="width: 30px;">&nbsp;</td>
      <td style="width: 640px; padding-top: 30px;">
      <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
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
      <th style="color: #333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">{$product_type}</th>
      </tr>
      </thead>
      <tbody>
      <tr>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$number}</td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$product}</td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$expiry} <br /><span style="color: #ad7b33; font-family: Arial, sans-serif; font-size: 12px;">Expiring Soon!</span></td>
      <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{$deletionDate}</td>
      </tr>
      </tbody>
      </table>
      <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">&nbsp;</p>
      </td>
      <td style="background: #ffffff; border-right: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 247px;">&nbsp;</td>
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
      <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
      </tr>
      <tr>
    {$contact}
      </tr>
      </tbody>
      </table>
      </td>
      <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      </tbody>
      </table>
      <p>&nbsp;</p>']); 



         Template::where('id',13)->update(['name' => 'Auto payment successfull', 'type' => 13, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td style="width: 640px; padding-top: 30px;">
        <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
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
        <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Subscription was Successfully Renewed.</h1>
        </td>
        <td style="background: #ffffff; border-right: 1px solid #cccccc; border-top: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 122px;">&nbsp;</td>
        </tr>
        <tr style="height: 247px;">
        <td style="background: #ffffff; border-left: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 247px;">&nbsp;</td>
        <td style="background: #ffffff; padding: 0px; width: 560px; height: 247px;" align="left">
        <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">As our last notification stated, your subscription was going to end. We inform you that your subscription has been renewed and your credit card has been automatically charged.</p>
        <p>&nbsp;</p>
        <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr style="background-color: #f8f8f8;">
        <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 139.016px;" align="left" valign="top">Product</th>
        <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 152.672px;" align="left" valign="top">Currency</th>
        <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 100.773px;" align="left" valign="top">Total</th>
        <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 98.5391px;">OrderNumber</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 139.016px;" valign="top">{$product}</td>
        <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 152.672px;" valign="top">{$currency}</td>
        <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 100.773px;" valign="top">{$total}</td>
        <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 98.5391px;">&nbsp; &nbsp; &nbsp;{$number}</td>
        </tr>
        </tbody>
        </table>
        </td>
        <td style="background: #ffffff; border-right: 1px solid #cccccc; width: 40px; padding-top: 10px; padding-bottom: 10px; height: 247px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="padding: 20px 0 10px 0; width: 640px;" align="left">{$contact}
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
        </tr>
        <tr></tr>
        </tbody>
        </table>
        </td>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>']); 
       Template::where('id',15)->delete();
       Template::where('id',16)->delete();
       Template::where('id',17)->delete();
       Template::where('id',18)->delete();


       Template::where('id',19)->update(['name' => 'URGENT: Order has been deleted', 'type' => 19, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="width: 30px;">&nbsp;</td>
      <td style="width: 640px; padding-top: 30px;">
      <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
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
      <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
      <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Faveo Cloud - has been Deleted</h1>
      </td>
      <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      <tr>
      <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      <td style="background: #fff; padding: 0; width: 560px;" align="left">
      <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">We are reaching out to inform you that your Faveo Cloud product instance has expired, and as a result, we have proceeded with the deletion of your instance and associated details from our system. Unfortunately, this means that you will no longer be able to use the product.</p>
      <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Thank you For Choosing Faveo!</p>
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
      <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">&nbsp;</p>
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
      <td style="padding: 20px 0 10px 0; width: 640px;" align="left">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tbody>
      <tr>
      <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
      </tr>
      <tr>
     {$contact}
      </tr>
      </tbody>
      </table>
      </td>
      <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
      </tr>
      </tbody>
      </table>
      <p>&nbsp;</p>']); 




        Template::create(['id' => 20, 'name' => '[Faveo Cloud] New instance created', 'type' => 20, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
          <tbody>
           <tr>
           <td style="width: 30px;">&nbsp;</td>
           <td style="width: 640px; padding-top: 30px;">
           <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;">{$logo}</h2>
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
           <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {$name},<br /><br />
           <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Faveo Cloud Instance has been created</h1>
           </td>
           <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
           </tr>
           <tr>
           <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
           <td style="background: #fff; padding: 0; width: 560px;" align="left">
           <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">{{$message}}</p>
           <p>Thank-you for choosing Faveo!</p>
           <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">&nbsp;</p>
           </td>
           <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
           </tr>
           <tr>
           <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
           <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left">&nbsp;</td>
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
           <td style="color: #333; font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
           </tr>
           <tr>
          {$contact}
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

