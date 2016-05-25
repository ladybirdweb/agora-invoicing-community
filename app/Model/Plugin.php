<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $table = 'plugins';
    protected $fillable = ['name', 'path', 'status','type',];
}
