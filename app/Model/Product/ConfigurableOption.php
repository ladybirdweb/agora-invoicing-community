<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class ConfigurableOption extends BaseModel
{
    protected $table = 'configurable_options';
    protected $fillable = ['group_id', 'type', 'title', 'options', 'price'];
}
