<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $table = 'api_keys';
    protected $fillable = ['rzp_key', 'rzp_secret', 'apilayer_key', 'bugsnag_api_key',
    'zoho_api_key', 'msg91_auth_key', 'msg91_sender', 'twitter_consumer_key',
    'twitter_consumer_secret', 'twitter_access_token', 'access_tooken_secret', 'license_api_secret', 'license_api_url', 'nocaptcha_sitekey', 'captcha_secretCheck', 'update_api_url', 'update_api_secret', 'terms_url', 'pipedrive_api_key', ];
}
