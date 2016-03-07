<?php namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {

	protected $table = 'product_types';
        protected $fillable = ['name','description'];
        
        public function Product(){
        return $this->hasMany('App\Model\Product\Product');
    }
    public function delete() {
        
        
        $this->Product()->delete();
        
        
        return parent::delete();
    }

}
