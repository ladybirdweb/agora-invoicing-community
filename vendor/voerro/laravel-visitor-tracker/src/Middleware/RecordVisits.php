<?php

namespace Voerro\Laravel\VisitorTracker\Middleware;

use Closure;
use Voerro\Laravel\VisitorTracker\Tracker;

class RecordVisits
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
        Tracker::recordVisit();

        return $next($request);
    }
}
