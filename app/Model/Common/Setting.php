<?php

namespace App\Model\Common;

use App\BaseModel;

class Setting extends BaseModel
{
    protected $table = 'settings';
    protected $fillable = ['company', 'website', 'phone', 'logo', 'address', 'host', 'port', 'encryption', 'email', 'password', 'error_log', 'error_email',
            'cart', 'subscription_over', 'subscription_going_to_end', 'forgot_password', 'order_mail', 'welcome_mail', 'invoice_template', 'driver', ];

    public function getPasswordAttribute($value)
    {
        $value = \Crypt::decrypt($value);

        return $value;
    }
}
