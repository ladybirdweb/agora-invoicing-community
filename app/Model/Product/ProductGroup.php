<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductGroup extends Model
{
    protected $table = 'product_groups';
    protected $fillable = ['name', 'headline', 'tagline', 'available_payment', 'hidden', 'cart_link'];

    public function config()
    {
        return $this->hasMany('App\Model\Product\ConfigurableOption', 'group_id');
    }

    public function features()
    {
        return $this->hasMany('App\Model\Product\GroupFeatures', 'group_id');
    }

    public function delete()
    {
        $this->config()->delete();
        $this->features()->delete();
        parent::delete();
    }
}
