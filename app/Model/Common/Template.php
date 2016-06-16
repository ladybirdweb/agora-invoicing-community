<?php

namespace App\Model\Common;
use Illuminate\Database\Eloquent\Model;
use App\BaseModel;

class Template extends Model
{
    protected $table = 'templates';
    protected $fillable = ['name', 'data', 'type', 'url'];
    
    public function type(){
        return $this->hasOne('App\Model\Common\TemplateType','id');
    }
}
