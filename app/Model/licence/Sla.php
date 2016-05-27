<?php

namespace App\Model\licence;

use App\BaseModel;

class Sla extends BaseModel
{
    protected $table = 'slas';
    protected $fillable = ['licence_id', 'name', 'description', 'organization_id', 'service_provider_id', 'shortnote',
            'start_date', 'end_date', 'grace_period', ];
}
