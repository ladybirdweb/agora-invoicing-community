<?php

namespace App\Model\Product;

use App\BaseModel;

class Addon extends BaseModel
{
    protected $table = 'addons';
    protected $fillable = ['product', 'subscription', 'name',
     'description', 'regular_price', 'selling_price', 'tax_addon',
        'show_on_order', 'auto_active_payment', 'suspend_parent', ];

    public function relation()
    {
        return $this->hasMany('App\Model\Product\ProductAddonRelation');
    }

    public function delete()
    {
        $this->relation()->delete();

        return parent::delete();
    }
}
