<?php

namespace App\Model\Common;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class SocialMedia extends BaseModel
{
    protected $table = 'social_media';
    protected $fillable = ['class', 'fa_class', 'name', 'link'];
}
