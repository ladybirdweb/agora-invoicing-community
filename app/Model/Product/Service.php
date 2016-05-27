<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Service extends BaseModel
{
    protected $table = 'services';
    protected $fillable = ['name', 'description'];
}
