<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $table = 'api_keys';
    protected $fillable = ['rzp_key', 'rzp_secret', 'apilayer_key', 'bugsnag_api_key', 
    'zoho_api_key', 'msg91_auth_key'];
}
