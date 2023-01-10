<?php

namespace App\Model\Product;

use App\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UpgradeSettings extends BaseModel
{
    use HasFactory;
    protected $table = 'upgrade_settings';
    protected $guarded = [];
}
