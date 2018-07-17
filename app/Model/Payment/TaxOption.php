<?php

namespace App\Model\Payment;

use App\BaseModel;
use Spatie\Activitylog\Traits\LogsActivity;

class TaxOption extends BaseModel
{
	use LogsActivity;
    protected $table = 'tax_rules';
    protected static $logName = 'Tax Class';
    protected static $logAttributes = ['tax_enable', 'inclusive', 'shop_inclusive', 'cart_inclusive', 'rounding', 'Gst_no'];
     protected static $logOnlyDirty = true;
    protected $fillable = ['tax_enable', 'inclusive', 'shop_inclusive', 'cart_inclusive', 'rounding', 'Gst_no'];

  
   public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName == 'created') {
            return 'Tax Rule  <strong> '.$this->name.' </strong> was created';
        }

        if ($eventName == 'updated') {
            return 'Tax Rule<strong> '.$this->name.'</strong> was updated';
        }

        if ($eventName == 'deleted') {
            return 'Tax Rule<strong> '.$this->name.' </strong> was deleted';
        }

        return '';
    }
}