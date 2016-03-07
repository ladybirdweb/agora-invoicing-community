<?php

namespace App\Model\Front;

use Illuminate\Database\Eloquent\Model;

class FrontendPage extends Model
{
    protected $table = 'frontend_pages';
    protected $fillable = ['parent_page_id','slug','name','content','url','publish'];
}
