<?php

namespace App\Model\Payment;

use App\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;

class Tax extends BaseModel
{
    use LogsActivity;
    protected $table = 'taxes';
    protected $fillable = ['level', 'name', 'country', 'state', 'rate', 'active', 'tax_classes_id', 'compound'];
    protected static $logName = 'Tax';
    protected static $logAttributes = ['name', 'country', 'state', 'rate', 'active', 'tax_classes_id'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return 'Tax  <strong> '.$this->name.' </strong> was created';
        }

        if ($eventName == 'updated') {
            return 'Tax <strong> '.$this->name.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Tax <strong> '.$this->name.' </strong> was deleted';
        }

        return '';
    }

    public function taxClass()
    {
        return $this->belongsTo('App\Model\Payment\TaxClass', 'tax_classes_id');
    }
}
