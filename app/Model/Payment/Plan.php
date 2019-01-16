<?php

namespace App\Model\Payment;

use App\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;

class Plan extends BaseModel
{
    use LogsActivity;
    protected $table = 'plans';
    protected $fillable = ['name', 'product', 'allow_tax', 'days'];
    protected static $logName = 'Plans';
    protected static $logAttributes = ['name', 'product', 'days'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return 'Plan  <strong> '.$this->name.' </strong> was created';
        }

        if ($eventName == 'updated') {
            return 'Plan <strong> '.$this->name.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Plan <strong> '.$this->name.' </strong> was deleted';
        }

        return '';

        // return "Product  has been {$eventName}";
         // \Auth::user()->activity;
    }

    public function planPrice()
    {
        return $this->hasMany('App\Model\Payment\PlanPrice');
    }

    public function product()
    {
        return $this->hasMany('App\Model\Product\Product', 'id');
    }

    public function periods()
    {
        return $this->belongstoMany('App\Model\Payment\Period', 'plans_periods_relation')->withTimestamps();
    }

    public function delete()
    {
        $this->planPrice()->delete();
        parent::delete();
    }
}
