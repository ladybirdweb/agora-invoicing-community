<?php

namespace App\Model\Licence;

use Illuminate\Database\Eloquent\Model;

class LicencedOrganization extends Model
{
    protected $table = 'licenced_organizations';
    protected $fillable = ['organization_id','licence_name','licence_description','number_of_slas','price','payment_status'];
}
