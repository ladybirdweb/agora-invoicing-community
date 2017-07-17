<?php

namespace App\Model\Licence;

use App\BaseModel;

class LicencedOrganization extends BaseModel
{
    protected $table = 'licenced_organizations';
    protected $fillable = ['organization_id', 'licence_name', 'licence_description', 'number_of_slas', 'price', 'payment_status'];
}
