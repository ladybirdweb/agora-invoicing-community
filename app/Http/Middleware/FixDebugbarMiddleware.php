<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\Debugbar\Facade as Debugbar;


class FixDebugbarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $middlewareConfig = config('debugbar.middleware', []);

        foreach ($middlewareConfig as $alias => $class) {
            if (is_array($class)) {
                config(['debugbar.middleware.' . $alias => implode(',', $class)]);
            }
        }
        
        return $next($request);
    }
}
