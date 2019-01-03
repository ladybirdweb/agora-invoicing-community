<?php

namespace App\Model\License;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LicensePermission extends Model
{
   protected $table = 'license_permissions';
   protected $fillable = ['id', 'permissions'];
   protected static $logName = 'License Permission';

   protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return 'License Permission '.$this->name.' was created';
        }
		if ($eventName == 'updated') {
            return 'License Permission  <strong> '.$this->name.'</strong> was updated';
        }
        if ($eventName == 'deleted') {
            return 'License Permission <strong> '.$this->name.' </strong> was deleted';
        }
        return '';
    }

   public function licenseTypes()
   {
   	return $this->belongsToMany(LicenseType::class,'license_license_permissions')->withTimestamps();
   }
}
