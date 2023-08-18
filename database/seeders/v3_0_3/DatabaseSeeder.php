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

        TemplateType::create(['id' => 20, 'name' => 'cloud_created']);
    }
}

class TemplateTableSeeder extends Seeder
{
    public function run()
    {
        Template::where('id',2)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="width: 30px;"> </td>
        <td style="width: 640px; padding-top: 30px;">
        <h2 style="color: #333; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
        </td>
        <td style="width: 30px;"> </td>
        </tr>
        <tr>
        <td style="width: 30px;"> </td>
        <td style="width: 640px;  padding-top: 30px;">
        <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
        <p>Dear {{name}}, <br /><br /> Before you can login, you must active your account. Click <a href="{{url}}">{{url}}</a> to activate your account.<br /><br /> <strong>Your Profile & Control Panel Login</strong><br /><br /> You can start exploring our feature-rich Control Panel, which will allow you to manage all your Products, buy new Products, check all your transactions and more.<br /><br /> <strong>Login Details:</strong><br /> <strong>URL: </strong><a href="{{website_url}}">{{website_url}}</a> <br /> <strong>Username:</strong> {{username}}<br /><br /> <strong>Password:</strong> If you can not recall your current password, <a href="{{website_url}}/password/reset">click here</a> to request a new password to login.<br /><br /> Thank You.<br /> Regards,<br /> Faveo Helpdesk</p>Thank You.<br /> Regards,<br /> Faveo Helpdesk</p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        </tr>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        <td style="background: #fff; padding: 0; width: 560px;" align="left"> </td>
        <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        </tr>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid                        #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank">Verify Email </a></td>
        <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;"> </td>
        </tr>
        </tbody>
        </table>
        </td>
        <td style="width: 30px;"> </td>
        </tr>
        <tr>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;"> </td>
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
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;"> </td>
        </tr>
        </tbody>
        </table>
        <p> </p>']);
        
        
        
        Template::where('id',4)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td style="width: 640px; padding-top: 30px;">
        <h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width: 30px;">&nbsp;</td>
        <td style="width: 640px; padding-top: 30px;">
        <table style="width: 640px;border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
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
        <p><a class="moz-txt-link-abbreviated" href="{$serialkeyurl}" target="_blank" rel="noopener"> Click Here</a> to get your License Code.</p>
        <p><a class="moz-txt-link-abbreviated" href="{{knowledge_base}}/category-list/installation-and-upgrade-guide"> Refer To Our Knowledge Base</a> for further installation assistance</p>
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
        <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
        </td>
        <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
        <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
        </td>
        <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
        <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /> <a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /> <a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
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
        
        
         Template::where('id',5)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
        <p>Dear {{name}},<br /><br /> A request to reset password was received from your account.&nbsp; Use the link below to reset your password and login.<br /><br /> <strong>Link:</strong>&nbsp; <a href="{{url}}">{{url}}</a><br /><br /> Thank You.<br /> Regards,<br /> Faveo Helpdesk<br /><br /> <strong>IMP:</strong> If you have not initiated this request, <a href="{{contact-us}}/contact-us/">report it to us immediately</a>.<br /><br /> <em>This is an automated email, please do not reply.</em></p>
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
        <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
        </td>
        <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
        <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
        </td>
        <td style="color: #333; font-family: Arial, sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
        <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /><a class="moz-txt-link-abbreviated" href="mailto:support@ladybirdweb.com">accounts@ladybirdweb.com</a><br /><a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
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
        
         Template::where('id',6)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
            
             Template::where('id',7)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
            
            
                 
             Template::where('id',8)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td style="width: 30px;">&nbsp;</td>
            <td style="width: 640px; padding-top: 30px;">
            <h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
            </td>
            <td style="width: 30px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="width: 30px;">&nbsp;</td>
            <td style="width: 640px; padding-top: 30px;">
            <table style="width: 640px;border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
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
            <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
            </td>
            <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
            <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
            </td>
            <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
            <p style="line-height: 20px;">Ladybird Web Solution Pvt Ltd<br /> <a class="moz-txt-link-abbreviated" href="mailto:accounts@ladybirdweb.com">accounts@ladybirdweb.com</a><br /> <a class="moz-txt-link-freetext" href="https://www.faveohelpdesk.com">www.faveohelpdesk.com</a><br /> Tel: +91 80 3075 2618</p>
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
            
            Template::where('id',9)->update(['data' => '<p>Dear {{name}},</p>
            <p>This is {{manager_first_name}} {{manager_last_name}}.</p>
            <p>From now onwards I will be your one point of contact. I will followup with you as well as with our team. Please feel free to get in touch with me anytime if you have any issues with regards to your account. You can also add me on Skype. My ID is mentioned in my signature. It is a pleasure to have you on board and I look forward to effective conversations with you in future.</p>
            <p>Hope you have a great day.</p>
            <p>Regards,</p>
            <p>{{manager_first_name}} {{manager_last_name}}</p>
            <p>Sales Manager,<br />Faveo Helpdesk<br />Mobile: {{manager_code}} {{manager_mobile}}<br />Skype ID: {{manager_skype}}<br />Email: {{manager_email}}</p>']);
            
            Template::where('id',10)->update(['data' => '<p>Dear {{name}},</p>
            <p>This is {{manager_first_name}} {{manager_last_name}}.</p>
            <p>From now onwards I will be your one point of contact. I will followup with you as well as with our team. Please feel free to get in touch with me anytime if you have any issues with regards to your account. You can also add me on Skype. My ID is mentioned in my signature. It is a pleasure to have you on board and I look forward to effective conversations with you in future.</p>
            <p>Hope you have a great day.</p>
            <p>Regards,</p>
            <p>{{manager_first_name}} {{manager_last_name}}</p>
            <p>Account Manager,<br />Faveo Helpdesk<br />Mobile: {{manager_code}} {{manager_mobile}}<br />Skype ID: {{manager_skype}}<br />Email: {{manager_email}}</p>']);
            
                Template::where('id',12)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                <td style="background: #ffffff; border-top: 1px solid #cccccc; padding: 40px 0px 10px; width: 560px; height: 122px;" align="left">Dear {{name}},<br /><br />
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
                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{number}}</td>
                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{product}}</td>
                <td style="border-bottom: 1px; color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{expiry}} <br /><span style="color: #ad7b33; font-family: Arial, sans-serif; font-size: 12px;">Expiring Soon!</span></td>
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
                
                Template::where('id',13)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                <td style="background: #ffffff; border-top: 1px solid #cccccc; padding: 40px 0px 10px; width: 560px; height: 122px;" align="left">Dear {{name}},<br /><br />
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
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 139.016px;" valign="top">{{product}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 152.672px;" valign="top">{{currency}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 100.773px;" valign="top">{{total}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 98.5391px;">&nbsp; &nbsp; &nbsp;{{number}}</td>
                </tr>
                </tbody>
                </table>
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">If you need more details, please feel free to head to our <span style="color: #e03e2d;"><strong>CUSTOMER SUPPORT</strong></span> team.</p>
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
                
                
                  Template::where('id',14)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
                <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Oh no, your payment failed.</h1>
                </td>
                <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                </tr>
                <tr>
                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                <td style="background: #fff; padding: 0; width: 560px;" align="left">
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Your Payment failed for your subscription. Dont worry, Well try again over the next few days.</p>
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">To keep your product active you may need to update your payment, Please use the below link to renew your order.&nbsp;&nbsp;</p>
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;"><strong>Reason for Failure:</strong></p>
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">{{exception}}.</p>
                <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                <thead>
                <tr style="background-color: #f8f8f8;">
                <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 139.516px;" align="left" valign="top">Order ID</th>
                <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 138.031px;" align="left" valign="top">Product</th>
                <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 83.6016px;" align="left" valign="top">Expiry Date</th>
                <th style="color: #333333; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px; width: 35.8281px;">Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 139.516px;" valign="top">{{number}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 138.031px;" valign="top">{{product}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 83.6016px;" valign="top">{{expiry}}</td>
                <td style="border-bottom: 1px; color: #333333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px; width: 35.8281px;">{{total}}</td>
                </tr>
                </tbody>
                </table>
                <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click the button below to login to your Control Panel and renew your orders.</p>
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
                
                
                  Template::where('id',16)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                <td style="width: 30px;">&nbsp;</td>
                <td style="width: 640px; padding-top: 30px;">
                <h2 style="color: #333; font-family: Arial,sans-serif; font-size: 18px; font-weight: bold; padding: 0; margin: 0;"><img src="https://billing.faveohelpdesk.com/common/images/faveo1.png" alt="Faveo Helpdesk" /></h2>
                </td>
                <td style="width: 30px;">&nbsp;</td>
                </tr>
                <tr>
                <td style="width: 30px;">&nbsp;</td>
                <td style="width: 640px; padding-top: 30px;">
                <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px 0 0 0;">&nbsp;</td>
                <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
                <h1 style="color: #0088cc; font-family: Arial,sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Thanks for your {{product}} order</h1>
                <br />Your order and payment details are below.</td>
                <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0 5px 0 0;">&nbsp;</td>
                </tr>
                <tr>
                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                <td style="background: #fff; padding: 0; width: 560px;" align="left">
                <table style="margin: 25px 0 30px 0; width: 560px; border: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
                <thead>
                <tr style="background-color: #f8f8f8;">
                <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Order Number</th>
                <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Product</th>
                <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Deploy</th>
                <th style="color: #333; font-family: Arial,sans-serif; font-size: 14px; font-weight: bold; line-height: 20px; padding: 15px 8px;" align="left" valign="top">Expiry</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{number}}</td>
                <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{product}}</td>
                <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top"><a style="background: #00aeef; border: 1px solid #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{url}}" target="_blank" rel="noopener"> Deploy</a></td>
                <td style="border-bottom: 1px solid#ccc; color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; padding: 15px 8px;" valign="top">{{expiry}}</td>
                </tr>
                </tbody>
                </table>
                <p><a class="moz-txt-link-abbreviated" href="{{serialkeyurl}}" target="_blank" rel="noopener"> Click Here</a> to get your License Code.</p>
                <p><a class="moz-txt-link-abbreviated" href="{{knowledge_base}}/category-list/installation-and-upgrade-guide"> Refer To Our Knowledge Base</a> for further installation assistance</p>
                <p style="color: #333; font-family: Arial,sans-serif; font-size: 14px; line-height: 20px; text-align: left;">Click below to login to your Control Panel to view invoice or to pay for any pending invoice.</p>
                </td>
                <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                </tr>
                <tr>
                <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
                <td style="background: #fff; padding: 20px 0 50px 0; width: 560px;" align="left"><a style="background: #00aeef; border: 1px solid #0088CC; padding: 10px 20px; border-radius: 5px; font-size: 14px; font-weight: bold; color: #fff; outline: none; text-shadow: none; text-decoration: none; font-family: Arial,sans-serif;" href="{{invoiceurl}}" target="_blank" rel="noopener"> View Invoice </a></td>
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
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">SALES CONTACT</td>
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">TECHNICAL SUPPORT</td>
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 12px; font-weight: bold; padding-bottom: 0; padding-left: 25px;">BILLING CONTACT</td>
                </tr>
                <tr>
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                <p style="line-height: 20px;"><a class="moz-txt-link-abbreviated" href="mailto:sales@faveohelpdesk.com">sales@faveohelpdesk.com</a><br />Tel: +91 80 3075 2618</p>
                </td>
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
                <p style="line-height: 20px;"><a class="moz-txt-link-freetext" href="https://www.support.faveohelpdesk.com">www.support.faveohelpdesk.com</a></p>
                </td>
                <td style="color: #333; font-family: Arial,sans-serif; font-size: 11px; padding-left: 25px;" valign="top">
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
                
                
                Template::where('id',19)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                        <p>Thank you For Choosing Faveo!</p>
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
                        
                        
                         Template::where('id',19)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
                        <p>Thank you For Choosing Faveo!</p>
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
                            
                            
                            

        Template::create(['id' => 20, 'name' => '[Faveo Cloud] New instance created', 'type' => 20, 'url' => 'null', 'data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
            <table style="width: 640px; border-bottom: 1px solid #ccc;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
            <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
            <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">Dear {{name}},<br /><br />
            <h1 style="color: #0088cc; font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; padding: 0; margin: 0;">Your Faveo Cloud Instance has been created</h1>
            </td>
            <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
            </tr>
            <tr>
            <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
            <td style="background: #fff; padding: 0; width: 560px;" align="left">
            <p style="color: #333; font-family: Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: left;">{{message}}</p>
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

