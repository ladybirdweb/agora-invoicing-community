<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

//use Laravel\Cashier\Billable;
//use LinkThrow\Billing\CustomerBillableTrait;
//use App\Model\Common\Website;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable,
        CanResetPassword;

// use Billable;
   // use CustomerBillableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'company', 'zip', 'state', 'town', 'mobile',
        'email', 'password', 'role', 'active', 'profile_pic', 'address', ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order');
    }

//    public function subscriptionmodels() {
//        // Return an Eloquent relationship.
//        return $this->hasMany('Website');
//
//
//    }

    public function delete()
    {
        $this->order()->delate();

        return parent::delete();
    }
}
