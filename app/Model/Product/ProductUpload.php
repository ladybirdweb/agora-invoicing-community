<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductUpload extends Model
{
    protected $table = 'product_uploads';
    protected $fillable = ['product_id', 'title', 'description', 'version', 'file'];

    public function product()
    {
        return $this->belongsTo('App\Model\Product\Product');
    }

    public function order()
    {
        return $this->belongsTo('App\Model\Order\Order');
    }
}
