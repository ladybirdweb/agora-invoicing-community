<?php

use Illuminate\Database\Seeder;

class FrontPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('frontend_pages')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        \App\Model\Front\FrontendPage::create([
            'slug'          => 'pricing',
            'name'          => 'pricing',
            'parent_page_id'=> 0,
            'content'       => '
                            <div class="col-md-3 col-sm-6">
                            <div class="plan">
                                <div class="plan-header">
                                    <h3>{{name}}</h3>
                                </div>
                                <div class="plan-price">
                                    <span class="price">{{price}}</span>
                                    
                                    <label class="price-label">{{price-description}}</label>
                                </div>
                                <div class="plan-features">
                                    <ul>
                                    <li>{{feature}}</li>
                                </ul>
                                     
                                </div>
                                <div class="plan-footer">
                                <div class="subscription">{{subscription}}</div><br/>
                                <div>{{url}} </div>
                                </div>
                                
                            </div>
                        </div>
                           
                        ',
            'url'     => url('/my-invoices'),
            'type'    => 'cart',
            'publish' => 1,
            'hidden'  => 1,
        ]);

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('frontend_pages')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        \App\DefaultPage::create([
            'page_id'           => '1',
            'page_url'          => 'http://'.url('/my-invoices'),
        ]);
    }
}
