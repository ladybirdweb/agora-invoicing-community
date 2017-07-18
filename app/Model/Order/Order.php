<?php

namespace App\Model\Order;

use App\BaseModel;
use App\Model\Product\Subscription;
use Illuminate\Contracts\Encryption\DecryptException;

class Order extends BaseModel
{
    protected $table = 'orders';
    protected $fillable = ['client', 'order_status', 'invoice_item_id',
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
        return $this->hasOne('App\Model\Product\Subscription');
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

    public function item()
    {
        return $this->belongsTo('App\Model\Order\InvoiceItem');
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
        $d = $date->setTimezone($tz);

        return $d;
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

    public function getDomainAttribute($value)
    {
        try {
            if (ends_with($value, '/')) {
                $value = substr_replace($value, '', -1, 0);
            }

            return $value;
        } catch (DecryptException $ex) {
            return $value;
        }
    }

    public function setDomainAttribute($value)
    {
        $this->attributes['domain'] = $this->get_domain($value);
    }

    public function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        if (!$domain) {
            $domain = $pieces['path'];
        }

        return strtolower($domain);
    }
}
