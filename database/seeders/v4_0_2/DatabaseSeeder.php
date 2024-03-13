<?php

namespace Database\Seeders\v4_0_2;

use App\ReleaseType;
use App\Model\Common\Template;
use Illuminate\Database\Seeder;
use App\Model\License\LicenseType;
use App\Model\Product\ProductUpload;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->template();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        ProductUpload::where('release_type_id',2)->delete();
        ReleaseType::truncate();
        LicenseType::where('id',7)->delete();

        $this->call([
            ReleaseTypeSeeder::class,
            ProductUploadSeeder::class,
            LicenseTypeSeeder::class
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
    }

    public function template(){
        Template::where('id',21)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
        <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px 0 0 0;">&nbsp;</td>
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
        <p>Dear {{title}} Team,<br /><br />Below form was submitted on the website {{request_url}}<br /><br /><b>Name:</b> {{name}}<br /><b>Email:</b> {{email}}<br /><b>Message:</b> {{message}}<br /><b>Mobile:</b> {{mobile}}<br /><b>IP Address:</b> {{ip_address}}<br /><br />Thank You<br />{{title}}</p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0 5px 0 0;">&nbsp;</td>
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

        Template::where('id', 22)->update(['data' => '<table style="background: #f2f2f2; width: 700px;" border="0" cellspacing="0" cellpadding="0">
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
        <td style="background: #fff; border-left: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 5px 0 0 0;">&nbsp;</td>
        <td style="background: #fff; border-top: 1px solid #ccc; padding: 40px 0 10px 0; width: 560px;" align="left">
        <p>Dear {{title}} Team,<br /><br />Below form was submitted on the website {{request_url}}<br /><br /><b>Name:</b> {{name}}<br /><b>Email:</b> {{email}}<br /><b>Message:</b> {{message}}<br /><b>Mobile:</b> {{mobile}}<br /><b>IP Address:</b> {{ip_address}}<br /><br />Thank You<br />{{title}}</p>
        </td>
        <td style="background: #fff; border-right: 1px solid #ccc; border-top: 1px solid #ccc; width: 40px; padding-top: 10px; padding-bottom: 10px; border-radius: 0 5px 0 0;">&nbsp;</td>
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



