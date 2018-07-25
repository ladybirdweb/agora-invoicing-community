<?php

namespace App\Model\Product;

use App\BaseModel;

class Type extends BaseModel
{
    protected $table = 'product_types';
    protected $fillable = ['name', 'description'];

    public function product()
    {
        return $this->hasMany('App\Model\Product\Product');
    }

    public function delete()
    {
        $this->Product()->delete();

        return parent::delete();
    }
}
