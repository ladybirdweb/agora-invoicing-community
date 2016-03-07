<?php namespace App\Plugins\Twilio\Model;

use Illuminate\Database\Eloquent\Model;

class Twilio extends Model {

	protected $table='Twilio';

	protected $fillable = ['account_sid','auth_token','from_number'];

}
