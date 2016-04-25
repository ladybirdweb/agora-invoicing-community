<?php

namespace App\Model\Common\Mailchimp;

use Illuminate\Database\Eloquent\Model;

class MailchimpLists extends Model
{
    protected $table = 'mailchimp_lists';
    protected $fillable = ['name','list_id'];
}
