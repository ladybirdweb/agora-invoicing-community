<?php

namespace App\Model\Common;

use App\BaseModel;

class State extends BaseModel
{
    protected $table = 'states_subdivisions';
    protected $primaryKey = 'state_subdivision_id';

    protected $fillable = [
        'state_subdivision_id', 'country_code_char2',
        'country_code_char3', 'state_subdivision_name',
        'state_subdivision_alternate_names', 'primary_level_name',
         'state_subdivision_code',
    ];

    public $timestamps = false;
}
