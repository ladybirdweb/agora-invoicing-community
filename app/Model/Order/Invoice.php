<?php

namespace App\Model\Order;

use App\BaseModel;

class Invoice extends BaseModel
{
    protected $table = 'invoices';
    protected $fillable = ['user_id', 'number', 'date', 'discount', 'discount_mode', 'coupon_code', 'grand_total', 'currency', 'status', 'description'];
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
        $tz = \Auth::user()->timezone()->first()->name;
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');

        return $date->setTimezone($tz);
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
