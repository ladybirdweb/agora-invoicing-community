<?php

namespace App\Model\Common\Mailchimp;

use Illuminate\Database\Eloquent\Model;

class MailchimpField extends Model
{
    protected $table = 'mailchimp_fields';
    protected $fillable = ['list_id', 'merge_id', 'name', 'type', 'options', 'required', 'tag'];
}
