<?php

namespace App\Model\Front;

use App\BaseModel;

class Widgets extends BaseModel
{
    protected $table = 'widgets';
    protected $fillable = ['name', 'type', 'publish', 'content'];
}
