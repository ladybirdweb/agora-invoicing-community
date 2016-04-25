<?php

namespace App\Model\Common\Mailchimp;

use Illuminate\Database\Eloquent\Model;

class MailchimpSetting extends Model
{
    protected $table = 'mailchimp_settings';
    protected $fillable = ['api_key', 'list_id', 'subscribe_status'];
}
