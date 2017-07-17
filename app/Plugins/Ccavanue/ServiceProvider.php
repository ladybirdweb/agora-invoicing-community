<?php

namespace App\Plugins\Ccavanue;

class ServiceProvider extends \App\Plugins\ServiceProvider
{
    public function register()
    {
        parent::register('Ccavanue');
    }

    public function boot()
    {
        parent::boot('Ccavanue');
    }
}
