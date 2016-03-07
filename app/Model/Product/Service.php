<?php namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

	protected $table = 'services';
        protected $fillable = ['name','description'];

}
