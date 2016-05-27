<?php

namespace App\Model\Common\Mailchimp;

use Illuminate\Database\Eloquent\Model;

use App\BaseModel;

class MailchimpLists extends BaseModel
{
    protected $table = 'mailchimp_lists';
    protected $fillable = ['name', 'list_id'];
}
