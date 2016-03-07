<?php namespace App\Model\licence;

use Illuminate\Database\Eloquent\Model;

class Licence extends Model {

	protected $table = 'licences';
        protected $fillable = ['name','description','number_of_sla','price','shoping_cart_link'];
        

}
