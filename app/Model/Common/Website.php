<?php

namespace App\Model\Common;

use App\BaseModel;
use LinkThrow\Billing\SubscriptionBillableTrait;

class Website extends BaseModel
{
    // use SubscriptionBillableTrait;

    public function customermodel()
    {
        // Return an Eloquent relationship.
        return $this->belongsTo('User', 'user_id');
    }
}
