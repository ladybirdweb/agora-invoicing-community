<?php

namespace App\Model\Product;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscription extends Model
{
    use LogsActivity;
    protected $table = 'subscriptions';
    protected $fillable = ['name', 'description', 'days', 'ends_at', 'update_ends_at',
    'user_id', 'plan_id', 'order_id', 'deny_after_subscription', 'version', 'product_id', 'support_ends_at', ];
    protected $dates = ['ends_at'];
    protected static $logName = 'Subscription';
    protected static $logAttributes = ['ends_at', 'update_ends_at', 'support_ends_at', 'user_id', 'plan_id', 'order_id',  'version', 'product_id'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            // dd($this->user()->get()->toArray(), 'cxzc');
            return 'Subscription for User_Id   <strong> '.$this->user_id.' </strong> was created';
        }

        if ($eventName == 'updated') {
            return 'Subscription for User <strong> '.$this->user_id.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Subscription for User <strong> '.$this->user_id.' </strong> was deleted';
        }

        return '';
    }

    public function plan()
    {
        return $this->belongsTo('App\Model\Payment\Plan');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Product\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function order()
    {
        return $this->belongsTo('App\Model\Order\Order');
    }

    // public function getEndsAtAttribute($value)
    // {
    //      $date1 = new DateTime($value);
    //     $tz = \Auth::user()->timezone()->first()->name;
    //     $date1->setTimezone(new DateTimeZone($tz));
    //     $date = $date1->format('M j, Y, g:i a ');

    //     return $date;
    // }

    //    public function delete() {
//
//
//        $this->Plan()->delete();
//
//
//        return parent::delete();
//    }
}
