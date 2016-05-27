<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class AccountActivate extends BaseModel
{
    protected $table = 'account_activates';
    protected $fillable = ['email', 'token'];
}
