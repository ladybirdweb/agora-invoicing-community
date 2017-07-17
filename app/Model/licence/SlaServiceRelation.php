<?php

namespace App\Model\licence;

use App\BaseModel;

class SlaServiceRelation extends BaseModel
{
    protected $table = 'sla_service_relations';
    protected $fillable = ['sla_id', 'service_id'];
}
