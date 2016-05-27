<?php

namespace App\Model\Front;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Widgets extends BaseModel
{
    protected $table = 'widgets';
    protected $fillable = ['name', 'type', 'publish', 'content'];
}
