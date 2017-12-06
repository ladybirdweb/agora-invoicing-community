<?php

namespace App\Model\User;

use App\BaseModel;
use Illuminate\Database\Eloquent\Model;

class AccountActivate extends Model
{
    protected $table = 'account_activates';
    protected $fillable = ['email', 'token'];
}
