<?php

namespace App\Http\Middleware;

use Closure;

class Install
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd($next);
        $env = base_path('.env');

        // 'driver' => env('DB_INSTALL', '1'),
        // dd(\File::exists($env) && env('DB_INSTALL')==1);
        if (\File::exists($env) && (\Config('database.DB_INSTALL') == 1)) {
            // dd(\File::exists($env), env('DB_INSTALL'));
            return $next($request);
        } elseif ((!\File::exists($env)) || (\Config('database.DB_INSTALL') == 0)) {
             return redirect()->route('LaravelInstaller::welcome');
        }
    }
}
