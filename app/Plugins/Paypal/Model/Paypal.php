<?php

namespace App\Plugins\Paypal\Model;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
    protected $table = 'paypal';

    protected $fillable = ['business', 'cmd', 'image_url', 'success_url', 'cancel_url', 'notify_url', 'paypal_url', 'currencies'];
}
