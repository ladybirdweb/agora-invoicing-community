<?php namespace App\Plugins\Twilio;
 
class ServiceProvider extends \App\Plugins\ServiceProvider {
 
    public function register()
    {
        parent::register('Twilio');
    }
 
    public function boot()
    {
        parent::boot('Twilio');
    }
 
}
