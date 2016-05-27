<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Password extends BaseModel
{
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token'];
    public $timestamps = false;
}
