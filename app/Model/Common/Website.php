<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;
use LinkThrow\Billing\SubscriptionBillableTrait;
use App\User;

class Website extends Model {

    use SubscriptionBillableTrait;

    public function customermodel() {
        // Return an Eloquent relationship.
        return $this->belongsTo('User', 'user_id');
    }

}
