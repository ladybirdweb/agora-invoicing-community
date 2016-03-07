<?php namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

	protected $table = 'invoices';
        protected $fillable = ['user_id','number','date','discount','discount_mode','coupon_code','grand_total'];
        
        public function InvoiceItem()
        {
            return $this->hasMany('App\Model\Order\InvoiceItem');
        }
        public function Order()
        {
            return $this->hasMany('App\Model\Order\Order');
        }
        
        public function delete()
        {
            $this->Order()->delete();
            $this->InvoiceItem()->delete();
            return parent::delete();
        }
}
