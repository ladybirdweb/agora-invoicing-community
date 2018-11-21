<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;

class StatusSetting extends Model
{
    protected $table = 'status_settings';
    protected $fillable = ['expiry_mail', 'activity_log_delete','license_status','github_status','mailchimp_status','twitter_status','msg91_status','emailverification_status'];
}
