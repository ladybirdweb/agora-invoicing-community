<?php

namespace App\Model\Order;

use App\Model\Product\Subscription;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Order extends BaseModel
{
    protected $table = 'orders';
    protected $fillable = ['client', 'order_status',
        'serial_key', 'product', 'domain', 'subscription', 'price_override', 'qty', 'invoice_id', 'number', ];

    public function invoice()
    {
        return $this->belongsTo('App\Model\Order\Invoice', 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'client');
    }

    public function subscription()
    {
        return $this->hasMany('App\Model\Product\Subscription');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Product\Product', 'product');
    }

    public function invoiceRelation()
    {
        return $this->hasMany('App\Model\Order\OrderInvoiceRelation');
    }

    public function invoiceItem()
    {
        return $this->hasManyThrough('App\Model\Order\InvoiceItem', 'App\Model\Order\Invoice');
    }

    public function delete()
    {
        $this->invoiceRelation()->delete();
        $this->subscription()->delete();

        parent::delete();
    }

    public function getOrderStatusAttribute($value)
    {
        return ucfirst($value);
    }

    public function getCreatedAtAttribute($value)
    {
        $tz = \Auth::user()->timezone()->first()->name;
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value);

        return $date->setTimezone($tz);
    }

    public function getSerialKeyAttribute($value)
    {
        try {
            $decrypted = \Crypt::decrypt($value);

            return $decrypted;
        } catch (DecryptException $ex) {
            return $value;
        }
    }
}
