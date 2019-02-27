<?php

namespace App\Model\Order;

use App\BaseModel;
use DateTime;
use DateTimeZone;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends BaseModel
{
    use LogsActivity;
    protected $table = 'invoices';
    protected $fillable = ['user_id', 'number', 'date', 'coupon_code', 'discount',
    'grand_total', 'currency', 'status', 'description', ];
    protected $dates = ['date'];
    protected static $logName = 'Invoice';

    protected static $logAttributes = ['user_id', 'number', 'date',
    'coupon_code', 'grand_total', 'currency', 'status', 'description', ];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {

        // dd(Activity::where('subject_id',)->pluck('subject_id'));
        if ($eventName == 'created') {
            return 'Invoice No.  <strong> '.$this->number.' </strong> was created';
        }

        if ($eventName == 'updated') {
            return 'Invoice No. <strong> '.$this->number.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Invoice No. <strong> '.$this->number.' </strong> was deleted';
        }

        return '';

        // return "Product  has been {$eventName}";
         // \Auth::user()->activity;
    }

    public function invoiceItem()
    {
        return $this->hasMany('App\Model\Order\InvoiceItem', 'invoice_id');
    }

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function subscription()
    {
        return $this->hasManyThrough('App\Model\Product\Subscription', 'App\Model\Order\Order');
    }

    public function orderRelation()
    {
        return $this->hasMany('App\Model\Order\OrderInvoiceRelation');
    }

    public function payment()
    {
        return $this->hasMany('App\Model\Order\Payment');
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function getCreatedAtAttribute($value)
    {
        $date1 = new DateTime($value);
        $tz = \Auth::user()->timezone()->first()->name;
        $date1->setTimezone(new DateTimeZone($tz));
        $date = $date1->format('M j, Y, g:i a ');

        return $date;
    }

    public function delete()
    {
        $this->orderRelation()->delete();
        $this->subscription()->delete();
        $this->order()->delete();

        $this->invoiceItem()->delete();
        $this->payment()->delete();

        return parent::delete();
    }
}
