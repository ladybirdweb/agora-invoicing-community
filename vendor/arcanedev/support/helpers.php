<?php

if ( ! function_exists('laravel_version')) {
    /**
     * Get laravel version or check if the same version
     *
     * @param  string|null $version
     *
     * @return string
     */
    function laravel_version($version = null) {
        $app = app();
        $appVersion = $app::VERSION;
        if (is_null($version)) {
            return $appVersion;
        }
        return substr($appVersion, 0, strlen($version)) === $version;
    }
}

if ( ! function_exists('route_is')) {
    /**
     * Check if route(s) is the current route.
     *
     * @param  array|string  $routes
     *
     * @return bool
     */
    function route_is($routes)
    {
        if ( ! is_array($routes)) {
            $routes = [$routes];
        }

        /** @var Illuminate\Routing\Router $router */
        $router = app('router');

        return call_user_func_array([$router, 'is'], $routes);
    }
}
