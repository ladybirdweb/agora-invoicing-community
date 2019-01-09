<?php

namespace App\Model\Product;

use App\BaseModel;

class ProductGroup extends BaseModel
{
    protected $table = 'product_groups';
    protected $fillable = ['id', 'name', 'headline', 'tagline', 'available_payment', 'hidden', 'cart_link', 'pricing_templates_id'];

    public function config()
    {
        return $this->hasMany('App\Model\Product\ConfigurableOption', 'group_id');
    }

    public function features()
    {
        return $this->hasMany('App\Model\Product\GroupFeatures', 'group_id');
    }

    public function product()
    {
        return $this->hasMany('App\Model\Product\Product', 'group');
    }

    public function pricingTemplate()
    {
        return $this->belongsTo('App\Model\Common\PricingTemplate');
    }

    public function delete()
    {
        $this->config()->delete();
        $this->features()->delete();
        parent::delete();
    }
}
