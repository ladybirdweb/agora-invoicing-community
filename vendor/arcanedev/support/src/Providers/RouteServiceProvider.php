<?php namespace Arcanedev\Support\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class     RouteServiceProvider
 *
 * @package  Arcanedev\Support\Laravel\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  as(string $name)
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  domain(string $domain)
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  middleware(array|string $middleware)
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  name(string $name)
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  namespace(string $namespace)
 * @method  \Arcanedev\Support\Routing\RouteRegistrar  prefix(string $prefix)
 * @method  void                                       group(...$mixed)
 *
 * @method  \Illuminate\Routing\Route  get(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  post(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  put(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  patch(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  delete(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  options(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  any(string $uri, \Closure|array|string|null $action = null)
 * @method  \Illuminate\Routing\Route  match(array|string $methods, string $uri, \Closure|array|string|null $action = null)
 *
 * @method  void  resource(string $name, string $controller, array $options = [])
 * @method  void  resources(array $resources)
 *
 * @method  void  pattern(string $key, string $pattern)
 * @method  void  patterns(array $patterns)
 *
 * @method  void  model(string $key, string $class, \Closure|null $callback = null)
 * @method  void  bind(string $key, string|\Closure $binder)
 *
 * @method  \Illuminate\Routing\Router  aliasMiddleware(string $name, string $class)
 * @method  \Illuminate\Routing\Router  hasMiddlewareGroup(string $name)
 * @method  \Illuminate\Routing\Router  middlewareGroup(string $name, array $middleware)
 * @method  \Illuminate\Routing\Router  prependMiddlewareToGroup(string $group, string $middleware)
 * @method  \Illuminate\Routing\Router  pushMiddlewareToGroup(string $group, string $middleware)
 */
abstract class RouteServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        //
    ];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->registerRouteMiddleware();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        parent::boot();

        $this->registerRouteBindings();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */
    /**
     * Register the route middleware.
     */
    protected function registerRouteMiddleware()
    {
        foreach ($this->routeMiddleware as $name => $class) {
            $this->aliasMiddleware($name, $class);
        }
    }

    /**
     * Register the route bindings.
     */
    protected function registerRouteBindings()
    {
        //
    }
}
