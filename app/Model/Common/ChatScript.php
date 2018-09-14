<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ChatScript extends Model
{
    use LogsActivity;
    protected $table = 'chat_scripts';
    protected $fillable = ['name', 'script'];
    protected static $logName = 'Chat Script';
    protected static $logAttributes = ['name', 'script'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        // dd(Activity::where('subject_id',)->pluck('subject_id'));
        if ($eventName == 'created') {
            return 'Chat Script'.$this->name.' was created';
        }

        if ($eventName == 'updated') {
            return 'Chat Script  <strong> '.$this->name.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Chat Script <strong> '.$this->name.' </strong> was deleted';
        }

        return '';

        // return "Product  has been {$eventName}";
         // \Auth::user()->activity;
    }
}
