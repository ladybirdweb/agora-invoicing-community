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

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
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
    protected $fillable = ['first_name', 'last_name', 'user_name', 'company', 'zip',
        'state', 'town', 'mobile',
        'email', 'password', 'role', 'active', 'profile_pic',
        'address', 'country', 'currency', 'timezone_id', 'mobile_code', 'bussiness',
        'company_type', 'company_size', 'ip', 'mobile_verified', 'position', 'skype', 'manager', ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function order()
    {
        return $this->hasMany('App\Model\Order\Order', 'client');
    }

    public function subscription()
    {
        // Return an Eloquent relationship.
        return $this->hasMany('App\Model\Product\Subscription');
    }

    public function invoiceItem()
    {
        return $this->hasManyThrough('App\Model\Order\InvoiceItem', 'App\Model\Order\Invoice');
    }

    public function orderRelation()
    {
        return $this->hasManyThrough('App\Model\Order\OrderInvoiceRelation', 'App\Model\Order\Invoice');
    }

    public function invoice()
    {
        return $this->hasMany('App\Model\Order\Invoice');
    }

    public function timezone()
    {
        return $this->belongsTo('App\Model\Common\Timezone');
    }

    public function getCreatedAtAttribute($value)
    {
        if (\Auth::user()) {
            $tz = \Auth::user()->timezone()->first()->name;
            $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC');

            return $date->setTimezone($tz);
        }

        return $value;
    }

    public function getProfilePicAttribute($value)
    {
        $image = \Gravatar::src($this->attributes['email']);
        if ($value) {
            $file = public_path('dist/app/users/'.$value);
            if (is_file($file)) {
                $mime = \File::mimeType($file);
                $extension = \File::extension($file);
                if (mime($mime) == 'image' && mime($extension) == 'image') {
                    $image = asset('dist/app/users/'.$value);
                } else {
                    unlink($file);
                }
            }
        }

        return $image;
    }

    public function payment()
    {
        return $this->hasMany('App\Model\Order\Payment');
    }

    public function bussiness()
    {
        $short = $this->attributes['bussiness'];
        $name = '--';
        $bussiness = \App\Model\Common\Bussiness::where('short', $short)->first();
        if ($bussiness) {
            $name = $bussiness->name;
        }

        return $name;
    }

    public function delete()
    {
        $this->invoiceItem()->delete();
        $this->orderRelation()->delete();
        $this->invoice()->delete();
        $this->order()->delete();
        $this->subscription()->delete();

        return parent::delete();
    }

    public function manager()
    {
        return $this->belongsTo('App\User', 'manager');
    }

    public function save(array $options = [])
    {
        $changed = $this->isDirty() ? $this->getDirty() : false;
        parent::save($options);
        $role = $this->role;
        if ($changed && checkArray('manager', $changed) && $role == 'user') {
            $auth = new Http\Controllers\Auth\AuthController();
            $auth->accountManagerMail($this);
        }
    }
}
