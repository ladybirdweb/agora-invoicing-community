<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto_renewal extends Model
{
    use HasFactory;

    protected $table = 'auto_renewals';

    protected $fillable = ['user_id','customer_id','invoice_number'];
}
