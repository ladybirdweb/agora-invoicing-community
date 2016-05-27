<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class GroupFeatures extends BaseModel
{
    protected $table = 'group_features';
    protected $fillable = ['group_id', 'features'];
}
