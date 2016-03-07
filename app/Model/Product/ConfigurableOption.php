<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ConfigurableOption extends Model
{
    protected $table = 'configurable_options';
    protected $fillable = ['group_id', 'type', 'title', 'options', 'price'];
}
