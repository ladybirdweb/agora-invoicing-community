<?php

namespace App\Model\Front;

use App\BaseModel;

class FrontendPage extends BaseModel
{
    protected $table = 'frontend_pages';
    protected $fillable = ['parent_page_id', 'slug', 'name', 'content', 'url', 'publish'];

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_replace(' ', '', $value);
    }
}
