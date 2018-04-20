<?php

namespace App\Model\Payment;

use App\BaseModel;

class Tax extends BaseModel
{
    protected $table = 'taxes';
    protected $fillable = ['level', 'name', 'country', 'state', 'rate', 'c_gst', 's_gst', 'i_gst', 'ut_gst', 'active', 'tax_classes_id', 'compound'];
}
