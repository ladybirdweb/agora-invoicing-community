<?php

namespace Database\Seeders\v3_0_6;
use Illuminate\Database\Seeder;
use App\Model\Common\PricingTemplate;
use App\Model\Front\Widgets;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Widgets::where('type','footer4')->delete();

        $this->call([PricingTemplateSeeder::class]);
        $this->command->info('Pricing Template Table Seeded!');
    }


    }

class PricingTemplateSeeder extends Seeder
{
    public function run()
    {
       PricingTemplate::truncate();

        PricingTemplate::create(['id' => 1,'data' => '<div class="">
            <div class="card border-radius-0 bg-color-light box-shadow-6 anim-hover-translate-top-10px transition-3ms">
                <div class="card-body py-5">
        
                    <div class="pricing-block">
                        <div class="text-center">
                            <h4 class="">{{name}}</h4>
        
                            <div class="content-switcher-wrapper">
                                <div class="content-switcher left-50pct transform3dx-n50 active" data-content-switcher-id="pricingTable1" data-content-switcher-rel="1">
                                    <div class="plan-price bg-transparent mb-4">
                                        <span class="price">{{price-year}}</span>
                                        <span class="strike">{{strike-priceyear}}</span>
                                        <label class="price-label">{{price-description}}</label><br>
                                        <div class="subscription table-responsive">{{subscription}}</div><br>
                                    </div>
                                </div>
                                <div class="content-switcher left-50pct transform3dx-n50" data-content-switcher-id="pricingTable1" data-content-switcher-rel="2">
                                    <div class="plan-price bg-transparent mb-4">
                                        <span class="price">{{price}}</span>
                                        <span class="strike">{{strike-price}}</span><br>
                                        <label class="price-label">{{pricemonth-description}}</label>
                                        <div class="subscription table-responsive">{{subscription}}</div><br>
                                    </div>
                                </div>
                            </div>
                        </div>
        <div class="plan-features">
        
                            <li>{{feature}}</li>
        										
        </div>
        
                        <div class="text-center mt-4 pt-2">
                            <a href="#" class="btn btn-modern">{{url}}</a>
                        </div>
        
                    </div>
        
                </div>
            </div>
        </div>
        ']);

        PricingTemplate::create(['id' => 2, 'data' => '<div class="">
    <div class="card border-radius-0 bg-color-light box-shadow-6 anim-hover-translate-top-10px transition-3ms">
        <div class="card-body py-5">

            <div class="pricing-block">
                <div class="text-center">
                    <h4 class="text-color-primary">{{name}}</h4>

                    <div class="content-switcher-wrapper">
                        <div class="content-switcher left-50pct transform3dx-n50 active" data-content-switcher-id="pricingTable1" data-content-switcher-rel="1">
                            <div class="plan-price bg-transparent mb-4">
                                <span class="price text-color-primary">{{price-year}}</span>
                                <span class="strike">{{strike-priceyear}}</span>
                                <label class="price-label">{{price-description}}</label><br>
                                <div class="subscription table-responsive">{{subscription}}</div><br>
                            </div>
                        </div>
                        <div class="content-switcher left-50pct transform3dx-n50" data-content-switcher-id="pricingTable1" data-content-switcher-rel="2">
                            <div class="plan-price bg-transparent mb-4">
                                <span class="price text-color-primary">{{price}}</span>
                                <span class="strike">{{strike-price}}</span><br>
                                <label class="price-label">{{pricemonth-description}}</label>
                                <div class="subscription table-responsive">{{subscription}}</div><br>
                            </div>
                        </div>
                    </div>
                </div>
<div class="plan-features blue">

                
                    <li>{{feature}}</li>
										
               
</div>

                <div class="text-center mt-4 pt-2">
                    <a href="#" class="btn btn-modern">{{url}}</a>
                </div>

            </div>

        </div>
    </div>
</div>']);
    }
}

