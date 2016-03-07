<?php

namespace App\Plugins\Ping\Model;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
    protected $table = 'ping';

    protected $fillable = ['secret_key', 'publishable_key', 'api_key', 'redirect_url', 'cancel_url', 'ccavanue_url'];
}
