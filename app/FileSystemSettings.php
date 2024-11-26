<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSystemSettings extends Model
{
    use HasFactory;

    protected $table = 'settings_filesystem';

    protected $fillable = [
        'disk', 'local_file_storage_path', 's3_bucket', 's3_region', 's3_access_key', 's3_secret_key', 's3_endpoint_url', 's3_path_style_endpoint', 's3_url'
    ];
}
