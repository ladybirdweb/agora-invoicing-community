<?php

namespace Database\Seeders\v4_0_2_3;

use App\FileSystemSettings;
use App\Model\Common\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->fileSystemSeeder();
        $this->templateSeeder();
    }
    public function fileSystemSeeder()
    {
        FileSystemSettings::create([
            'disk' => 'system',
            'local_file_storage_path' => storage_path('app/public'),
        ]);
    }

    public function templateSeeder()
    {
        Template::where('id',2)->update(['data' =>'<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
        <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 0px; padding-bottom: 0px;">&nbsp;</td>
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
        <p>Dear {{name}},&nbsp;<br/><br />Before you can log in, you need to activate your account. Please use the verification code below to complete the activation.<br />
        <div style="background:#f5f4f5;border-radius:4px;padding:20px;margin:20px 50px;">
    <div style="text-align:center;vertical-align:middle;font-size:30px">{{otp}}</div>
</div>

        <br /><strong>Your Profile &amp; Control Panel Login</strong><br /><br />You can start exploring our feature-rich Control Panel, which will allow you to manage all your Products, buy new Products, check all your transactions and more.<br /><br /><strong>Login Details:</strong><br /><br /><strong>URL: </strong><a href="{{website_url}}">{{website_url}}</a> <br /><strong>Username:</strong> {{username}}<br /><strong>Password:</strong> If you can not recall your current password, <a href="{{website_url}}/password/reset">click here</a> to request a new password to login.<br /><br />Thank You.</p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 0px; padding-bottom: 0px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="background: #fff; border-left: 1px solid #ccc; width: 40px; padding-top: 0px; padding-bottom: 0px;">&nbsp;</td>
        <td style="background: #fff; padding: 0; width: 560px;" align="left">&nbsp;</td>
        <td style="background: #fff; border-right: 1px solid #ccc; width: 40px; padding-top: 0px; padding-bottom: 0px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        </td>
        <td style="width: 30px;">&nbsp;</td>
        </tr>
        <tr>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        <td style="padding: 20px 0 10px 0; width: 640px;" align="left">{{contact}}
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
        </table>
        </td>
        <td style="width: 30px; padding-top: 10px; padding-bottom: 10px;">&nbsp;</td>
        </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>']);
    }
}
