<?php

//Route::get('payment-gateway/ping','App\Plugins\Ping\Controllers\SettingsController@Settings');
//Route::patch('payment-gateway/ping','App\Plugins\Ping\Controllers\SettingsController@postSettings');
//Route::get('payment-gateway/response','App\Plugins\Ping\Controllers\ProcessController@Response');
\Event::listen('App\Events\PaymentGateway', function ($event) {
    $controller = new App\Plugins\Ping\Controllers\ProcessController();
    echo $controller->Process($event->para);
});
