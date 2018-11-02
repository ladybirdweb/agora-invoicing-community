<?php

namespace App\Http\Middleware;

use Closure;
//use Illuminate\Routing\Middleware;
use Illuminate\Contracts\Auth\Guard;

class Admin
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

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
        // dd(\Auth::user()->role);
        if (\Auth::user()->role == 'admin') {
            return $next($request);
        } elseif (\Auth::user()->role == 'user') {
            $url = \Session::get('session-url');
            if ($url) {
                $content = \Session::get('content');
                $domain = \Session::get('domain');

                return redirect($url);
            }

            return redirect('/home');
        } else {
            \Auth::logout();
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('auth/login')->with('fails', 'Unauthorized');
            }
        }
    }
}
