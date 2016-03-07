<?php

Route::get('payment-gateway/ccavanue','App\Plugins\Ccavanue\Controllers\SettingsController@Settings');
Route::patch('payment-gateway/ccavanue','App\Plugins\Ccavanue\Controllers\SettingsController@postSettings');
Route::get('payment-gateway/response','App\Plugins\Ccavanue\Controllers\ProcessController@Response');
\Event::listen('App\Events\PaymentGateway', function($event)
{
    $controller = new App\Plugins\Ccavanue\Controllers\ProcessController();
    echo $controller->PassToPayment($event->para);
});