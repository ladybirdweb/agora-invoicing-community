<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
        
       
       $this->app->bind('\Symfony\Component\Mailer\MailerInterface::class', 'ProviderRepository');

            // $this->app->bind('\Symfony\Component\Mailer\MailerInterface::class',  'Illuminate\Foundation\ProviderRepository::class');

    }
}
