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
            'content'       => '<div class="pricing-table princig-table-flat">
                            <div class="col-md-3 col-sm-6">
                                <div class="plan">
                                    <h3>{{name}}<span><small style="font-color:white;!important">{{price}}</small></span></h3>
                                    <ul>
                                        <li>{{feature}}</li>
                                        
                                        
                                    </ul><br/>
                                    <ul>
                                    <li class="subscription">{{subscription}}</li>
                                         <li>{{url}}</li> 
                                         </ul>
                                </div>
                            </div>
                             </div>',
            'url'     => '',
            'type'    => 'cart',
            'publish' => 1,
            'hidden'  => 1,
        ]);
    }
}



                        

                  