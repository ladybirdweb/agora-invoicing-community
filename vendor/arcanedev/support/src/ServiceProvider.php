<?php namespace Arcanedev\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Arcanedev\Support\Providers\ServiceProvider as BaseServiceProvider;

/**
 * Class     ServiceProvider
 *
 * @package  Arcanedev\Support\Laravel
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @deprecated Use `Arcanedev\Support\Providers\ServiceProvider` instead.
 */
abstract class ServiceProvider extends BaseServiceProvider
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
