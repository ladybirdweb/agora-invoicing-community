<?php namespace App\Model\licence;

use Illuminate\Database\Eloquent\Model;

class SlaServiceRelation extends Model {

	protected $table = 'sla_service_relations';
        protected $fillable = ['sla_id','service_id'];

}
