<?php

namespace App\Model\Common\Mailchimp;

use Illuminate\Database\Eloquent\Model;

class MailchimpFieldAgoraRelation extends Model
{
    protected $table = 'mailchimp_field_agora_relations';
    protected $fillable = ['first_name','last_name','company','mobile','address','town','state','zip','active','role'];
}
