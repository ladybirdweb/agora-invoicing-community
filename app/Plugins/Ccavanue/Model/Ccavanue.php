<?php

namespace App\Plugins\Ccavanue\Model;

use Illuminate\Database\Eloquent\Model;

class Ccavanue extends Model
{
    protected $table = 'ccavenue';

    protected $fillable = ['merchant_id', 'access_code', 'working_key', 'redirect_url', 'cancel_url', 'ccavanue_url', 'currencies'];
}
