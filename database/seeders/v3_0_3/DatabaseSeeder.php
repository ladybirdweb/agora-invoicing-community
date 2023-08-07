<?php

namespace Database\Seeders\v3_0_3;
use Illuminate\Database\Seeder;
use App\Model\Common\TemplateType;
use App\Model\Common\StatusSetting;
use App\Model\Common\Template;
use App\Demo_page;
use App\Model\Common\PricingTemplate;
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


        $this->call([PricingTemplateSeeder::class]);
        $this->command->info('Pricing Template Table Seeded!');
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
class PricingTemplateSeeder extends Seeder
{
    public function run()
    {
        PricingTemplate::where('id',1)->update(['data' => '<div class="col-md-3 col-sm-6">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>{{name}}</h3>
                                </div>
                                <div class="plan-price">
                       <div class="content-switcher-wrapper">
                                                <div class="content-switcher left-50pct transform3dx-n50 active" data-content-switcher-id="pricingTable1" data-content-switcher-rel="1">
                                                
                    <span class="price">{{price}}</span>

                     <span class="strike">{{strike-price}}</span>
                                                                                <label class="price-label">{{price-description}}</label><br><br>
                     <div class="subscription table-responsive">{{subscription}}</div><br>

                    <div>{{url}} </div>                                                 
                                                                    </div>
                                                                    <div class="content-switcher left-50pct transform3dx-n50" data-content-switcher-id="pricingTable1" data-content-switcher-rel="2">
                                                                        
                    <span class="price">{{price-year}}</span>
                     <span class="strike">{{strike-priceyear}}</span>
                                                                                        <label class="price-label">{{price-description}}</label><br> <br>                                                                                        
                                                                            
                     <div class="subscription table-responsive">{{subscription}}</div><br>

                    <div>{{url}} </div>                                                 
                                                                    </div>
                                                                </div>
                    <br>


                                                    </div>

                      
                                                    <div class="plan-features">
                                                        <ul>
                                                        <li>{{feature}}</li>
                                                    </ul>
                                                         
                                                    </div>
                                                    <div class="plan-footer">
                                          
                                                  
                                                    </div>
                                                    
                            </div>
                        </div>']);

        PricingTemplate::create(['id' => 2, 'data' => '<div class="col-md-3 col-sm-6">
                 <div class="plan plan-featured transform-none">
                 <div class="plan-header bg-primary">
                 <h3>{{name}}</h3>
                 </div>
                 <div class="plan-price">
                 <div class="content-switcher-wrapper">
                                                                <div class="content-switcher left-50pct transform3dx-n50 active" data-content-switcher-id="pricingTable1" data-content-switcher-rel="1">
                                                                    
                                                                        <span class="price">{{price}}</span>
                 <span class="strike">{{strike-price}}</span>
                                                                                    <label class="price-label">{{price-description}}</label><br><br>
                 <div class="subscription">{{subscription}}</div>
                <br>
                <div>{{url}} </div>                                                 </div>
                                                                
                                                                <div class="content-switcher left-50pct transform3dx-n50" data-content-switcher-id="pricingTable1" data-content-switcher-rel="2">
                                                                    
                                                                        <span class="price">{{price-year}}</span>

                 <span class="strike">{{strike-priceyear}}</span>                                                                   <label class="price-label">{{price-description}}</label><br><br>
                 <div class="subscription">{{subscription}}</div>
                <br>
                <div>{{url}} </div>                                                 
                                                                </div>
                                                            </div><br>

                 </div>


                 <div class="plan-features">
                 <ul>
                 <li>{{feature}}</li>
                 </ul>
                 
                 </div>
                 <div class="plan-footer">

                 
                 </div>
                 
                 </div>
                 </div>']);
    }
}

