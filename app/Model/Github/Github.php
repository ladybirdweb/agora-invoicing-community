<?php

namespace App\Model\Github;

use Illuminate\Database\Eloquent\Model;

class Github extends Model
{
    protected $table = 'githubs';
    protected $fillable = ['client_id', 'client_secret', 'username', 'password'];
}
