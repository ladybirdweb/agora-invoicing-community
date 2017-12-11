<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;

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
        $env = base_path('.env');

        if (\File::exists($env) && env('DB_INSTALL') == 1) {
            return $next($request);
        } else {
            return redirect('/install');
        }
    }
}
