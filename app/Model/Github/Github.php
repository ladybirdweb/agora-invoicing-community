<?php

namespace App\Model\Github;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Github extends BaseModel
{
    protected $table = 'githubs';
    protected $fillable = ['client_id', 'client_secret', 'username', 'password'];
}
