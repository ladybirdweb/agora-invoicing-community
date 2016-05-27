<?php

namespace App\Model\Common;

use App\BaseModel;

class Template extends BaseModel
{
    protected $table = 'templates';
    protected $fillable = ['name', 'data', 'type', 'url'];
}
