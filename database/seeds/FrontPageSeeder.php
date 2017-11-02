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
            'slug'    => 'pricing',
            'name'    => 'pricing',
            'content' => '<div class="col-md-3 col-sm-6">
<div class="plan">
<h3>{{name}}</h3>
<ul><li><ul><li><strong>{{price}}</strong></li>
<li>{{feature}}</li>
</ul></li></ul><br /><ul><li>{{subscription}}</li>
<li>{{url}}</li>
</ul></div>
</div>',
            'url'     => '',
            'type'    => 'cart',
            'publish' => 1,
            'hidden'  => 1,
        ]);
    }
}
