<?php namespace Arcanedev\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class     ServiceProvider
 *
 * @package  Arcanedev\Support\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class ServiceProvider extends IlluminateServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The aliases collection.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Alias loader.
     *
     * @var \Illuminate\Foundation\AliasLoader
     */
    private $aliasLoader;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->aliasLoader = AliasLoader::getInstance();
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        //
    }

    /**
     * Register a binding with the container.
     *
     * @param  string|array          $abstract
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

    /**
     * Register a service provider.
     *
     * @param  \Illuminate\Support\ServiceProvider|string  $provider
     * @param  array                                       $options
     * @param  bool                                        $force
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    protected function registerProvider($provider, array $options = [], $force = false)
    {
        return $this->app->register($provider, $options, $force);
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
     * @param  array                                       $options
     * @param  bool                                        $force
     *
     * @return \Illuminate\Support\ServiceProvider|null
     */
    protected function registerConsoleServiceProvider($provider, array $options = [], $force = false)
    {
        if ($this->app->runningInConsole())
            return $this->registerProvider($provider, $options, $force);

        return null;
    }

    /**
     * Register aliases (Facades).
     */
    protected function registerAliases()
    {
        $loader = $this->aliasLoader;

        $this->app->booting(function() use ($loader) {
            foreach ($this->aliases as $class => $alias) {
                $loader->alias($class, $alias);
            }
        });
    }

    /**
     * Add an aliases to the loader.
     *
     * @param  array  $aliases
     *
     * @return self
     */
    protected function aliases(array $aliases)
    {
        foreach ($aliases as $class => $alias) {
            $this->alias($class, $alias);
        }

        return $this;
    }

    /**
     * Add an alias to the loader.
     *
     * @param  string  $class
     * @param  string  $alias
     *
     * @return self
     */
    protected function alias($class, $alias)
    {
        $this->aliases[$class] = $alias;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Services
     | -----------------------------------------------------------------
     */

    /**
     * Get the config repository instance.
     *
     * @return \Illuminate\Config\Repository
     */
    protected function config()
    {
        return $this->app['config'];
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function filesystem()
    {
        return $this->app['files'];
    }
}
