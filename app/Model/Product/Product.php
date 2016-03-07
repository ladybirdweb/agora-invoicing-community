<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'type', 'group', 'file', 'image', 'require_domain', 'category',
        'stock_control', 'stock_qty', 'sort_order', 'tax_apply', 'retired', 'hidden', 'multiple_qty', 'auto_terminate',
        'setup_order_placed', 'setup_first_payment', 'setup_accept_manually', 'no_auto_setup', 'shoping_cart_link', 'process_url', ];

    public function relation()
    {
        return $this->hasMany('App\Model\Product\ProductAddonRelation');
    }

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order');
    }

    public function type()
    {
        return $this->hasMany('App\Model\Product\Type');
    }

    public function price()
    {
        return $this->hasMany('App\Model\Product\Price');
    }

    public function PromoRelation()
    {
        return $this->hasMany('App\Model\Payment\PromoProductRelation', 'product_id');
    }

    public function delete()
    {
        $this->relation()->delete();
        $this->price()->delete();
        $this->PromoRelation()->delete();

        return parent::delete();
    }
}
