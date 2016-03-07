<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Carbon\Carbon;

class Plan extends Model
{
	use SoftDeletingTrait;
	protected $connection = 'billinglocal';
	protected $guarded = array('id');
	
	public function addInterval(Carbon $date)
	{
		switch ($this->interval) {
			case 'yearly':
				$date->addYear();
				break;
				
			case 'quarterly':
				$date->addMonths(3);
				break;
				
			case 'weekly':
				$date->addWeek();
				break;
				
			case 'monthly':
			default:
				$date->addMonth();
		}
		
		return $date;
	}
}
