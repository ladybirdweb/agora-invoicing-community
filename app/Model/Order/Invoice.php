<?php

namespace App\Model\Order;

use App\BaseModel;
use DateTime;
use DateTimeZone;

class Invoice extends BaseModel
{
    protected $table = 'invoices';
    protected $fillable = ['user_id', 'number', 'date', 'coupon_code', 'grand_total', 'currency', 'status', 'description'];
    protected $dates = ['date'];

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
        // $tz = \Auth::user()->timezone()->first()->name;
        // $date = \Carbon\Carbon::createFromFormat('D ,M j,Y, g:i a', $value, 'UTC');

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
