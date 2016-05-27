<?php

namespace App\Model\licence;

use App\BaseModel;

class Licence extends BaseModel
{
    protected $table = 'licences';
    protected $fillable = ['name', 'description', 'number_of_sla', 'price', 'shoping_cart_link'];
}
