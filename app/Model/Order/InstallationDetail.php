<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class InstallationDetail extends Model
{
    protected $table = 'installation_details';
    protected $fillable = ['installation_path', 'installation_ip', 'version', 'last_active'];
}
