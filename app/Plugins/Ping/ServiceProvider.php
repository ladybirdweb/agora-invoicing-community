<?php

namespace App\Plugins\Ping;

class ServiceProvider extends \App\Plugins\ServiceProvider
{
    public function register()
    {
        parent::register('Ping');
    }

    public function boot()
    {
        parent::boot('Ping');
    }
}
