<?php namespace Arcanedev\Support\Providers;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class     ServiceProvider
 *
 * @package  Arcanedev\Support\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class ServiceProvider extends IlluminateServiceProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register a service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  bool                                        $force
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    protected function registerProvider($provider, $force = false)
    {
        return $this->app->register($provider, $force);
    }

    /**
     * Register multiple service providers.
     *
     * @param  array  $providers
     */
    protected function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->registerProvider($provider);
        }
    }

    /**
     * Register a console service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  bool                                        $force
     *
     * @return \Illuminate\Support\ServiceProvider|null
     */
    protected function registerConsoleServiceProvider($provider, $force = false)
    {
        if ($this->app->runningInConsole())
            return $this->registerProvider($provider, $force);

        return null;
    }

    /**
     * Register the package's custom Artisan commands when running in console.
     *
     * @param  array  $commands
     */
    protected function registerCommands(array $commands)
    {
        if ($this->app->runningInConsole())
            $this->commands($commands);
    }

    /**
     * Register a binding with the container.
     *
     * @param  string                $abstract
     * @param  \Closure|string|null  $concrete
     * @param  bool                  $shared
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $this->app->bind($abstract, $concrete, $shared);
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string|array          $abstract
     * @param  \Closure|string|null  $concrete
     */
    protected function singleton($abstract, $concrete = null)
    {
        $this->app->singleton($abstract, $concrete);
    }
}
