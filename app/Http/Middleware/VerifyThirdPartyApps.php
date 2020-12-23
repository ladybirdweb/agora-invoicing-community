<?php

namespace App\Http\Middleware;

use App\ThirdPartyApp;
use Closure;

class VerifyThirdPartyApps
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
        $requestParameters = file_get_contents('php://input'); //get all the form parameters in the request
        $requestHeader = $request->header('signature'); //get signature sent in the request

        $app_secret = $keys = ThirdPartyApp::where('app_name', 'faveo_app_key')->value('app_secret');
        $signature = hash_hmac('sha256', $requestParameters, $app_secret); //hash the request parameter with the app secret

        if (hash_equals($signature, $requestHeader)) {
            return $next($request);
        } else {
            $result = ['status' => 'fails', 'message' => 'Invalid signature'];

            return response()->json(compact('result'));
        }
    }
}
