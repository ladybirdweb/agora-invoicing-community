<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;

class ChatScript extends Model
{
    protected $table = 'chat_scripts';
    protected $fillable = ['name','script'];

   
}
