<?php

Route::get('payment-gateway/ccavanue', 'App\Plugins\Ccavanue\Controllers\SettingsController@Settings');
Route::patch('payment-gateway/ccavanue', 'App\Plugins\Ccavanue\Controllers\SettingsController@postSettings');
Route::match(['get', 'post'], 'payment-gateway/response', 'App\Plugins\Ccavanue\Controllers\ProcessController@response');
\Event::listen('App\Events\PaymentGateway', function ($event) {
    $controller = new App\Plugins\Ccavanue\Controllers\ProcessController();
    echo $controller->PassToPayment($event->para);
});
