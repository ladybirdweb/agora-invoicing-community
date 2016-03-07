<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class GroupFeatures extends Model
{
    protected $table = 'group_features';
    protected $fillable = ['group_id','features'];
    
}
