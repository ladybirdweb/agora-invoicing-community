<?php

namespace App\Http\Middleware;

use Closure;

class IsInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $env = base_path('.env');
        if (! $this->alreadyInstalled()) {
            return $next($request);
        } else {
            if ($request->isJson()) {
                $url = url('/');
                $result = ['fails' => 'already installed', 'api' => $url];

                return response()->json(compact('result'));
            } else {
                return redirect('/');
            }
        }
    }

    public function alreadyInstalled()
    {
        if (file_exists(storage_path('installed'))) {
            unlink(storage_path('installed'));
        }

        return file_exists(storage_path('installed'));
    }
}
