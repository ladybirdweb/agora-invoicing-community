<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class FaveoCloud extends Model
{
    protected $table = 'faveo_cloud';
    protected $fillable = ['cloud_central_domain', 'cron_server_url', 'cron_server_key'];
    protected static $logName = 'Cloud detail';
    protected static $logAttributes = ['cloud_central_domain', 'cron_server_url', 'cron_server_key'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return 'Cloud detail'.$this->name.' was created';
        }

        if ($eventName == 'updated') {
            return 'Cloud detail  <strong> '.$this->name.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Cloud detail <strong> '.$this->name.' </strong> was deleted';
        }

        return '';

    }
}
