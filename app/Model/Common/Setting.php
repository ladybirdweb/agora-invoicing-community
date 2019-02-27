<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['company', 'website', 'phone', 'logo',
    'address', 'host', 'port', 'encryption', 'email', 'password',
    'error_log', 'error_email', 'state', 'city', 'country',
        'invoice', 'download', 'subscription_over', 'subscription_going_to_end',
        'forgot_password', 'order_mail', 'welcome_mail', 'invoice_template',
        'driver', 'admin_logo', 'title', 'favicon_title', 'fav_icon',
        'company_email', 'favicon_title_client', 'default_currency', 'default_symbol', 'file_storage', ];

    public function getPasswordAttribute($value)
    {
        if ($value) {
            $value = \Crypt::decrypt($value);
        }

        return $value;
    }

    public function setPasswordAttribute($value)
    {
        $value = \Crypt::encrypt($value);
        $this->attributes['password'] = $value;
    }
}
