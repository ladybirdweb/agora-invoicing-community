<?php

namespace App\Model\Product;

use App\BaseModel;

class Product extends BaseModel
{
    protected $table = 'products';
    protected $fillable = ['name', 'description', 'type', 'group', 'file', 'image', 'require_domain', 'category',
        'stock_control', 'stock_qty', 'sort_order', 'tax_apply', 'retired', 'hidden', 'multiple_qty', 'auto_terminate',
        'setup_order_placed', 'setup_first_payment', 'setup_accept_manually', 'no_auto_setup', 'shoping_cart_link', 'process_url', 'github_owner', 'github_repository',
        'deny_after_subscription', 'version', 'parent', 'subscription', ];

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order');
    }

    public function type()
    {
        return $this->belongsTo('App\Model\Product\Type', 'type');
    }

    public function price()
    {
        return $this->hasMany('App\Model\Product\Price');
    }

    public function PromoRelation()
    {
        return $this->hasMany('App\Model\Payment\PromoProductRelation', 'product_id');
    }

    public function tax()
    {
        return $this->hasMany('App\Model\Payment\TaxProductRelation', 'product_id');
    }

    public function delete()
    {
        $this->tax()->delete();
        $this->price()->delete();
        $this->PromoRelation()->delete();

        return parent::delete();
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            $image = asset('dist/product/images/No-image-found.jpg');
        } else {
            $image = asset("dist/product/images/$value");
        }

        return $image;
    }

    public function setParentAttribute($value)
    {
        $value = implode(',', $value);
        $this->attributes['parent'] = $value;
    }

    public function getParentAttribute($value)
    {
        $value = explode(',', $value);

        return $value;
    }

    public function planRelation()
    {
        $related = "App\Model\Payment\Plan";

        return $this->hasMany($related, 'product');
    }

    public function plan()
    {
        $plan = $this->planRelation()->first();

        return $plan;
    }
}
