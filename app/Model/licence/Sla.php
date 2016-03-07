<?php namespace App\Model\licence;

use Illuminate\Database\Eloquent\Model;

class Sla extends Model {

	protected $table = 'slas';
        protected $fillable = ['licence_id','name','description','organization_id','service_provider_id','shortnote',
            'start_date','end_date','grace_period'];

}
