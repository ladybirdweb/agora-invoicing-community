<?php

namespace App\Plugins\Paypal;

class ServiceProvider extends \App\Plugins\ServiceProvider
{
    public function register()
    {
        parent::register('Paypal');
    }

    public function boot()
    {
        parent::boot('Paypal');
    }
}
