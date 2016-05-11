<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    protected $table = 'social_media';
    protected $fillable = ['class', 'fa_class', 'name', 'link'];
}
