<?php

\Event::listen('App\Events\PaymentGateway', function ($event) {
    $controller = new App\Plugins\Paypal\Controllers\ProcessController();
    echo $controller->PassToPayment($event->para);
});
Route::get('payment-gateway/paypal', 'App\Plugins\Paypal\Controllers\SettingsController@Settings');
Route::patch('payment-gateway/paypal', 'App\Plugins\Paypal\Controllers\SettingsController@postSettings');
Route::match(['get', 'post'], 'payment-gateway/paypal/response', 'App\Plugins\Paypal\Controllers\ProcessController@response');
Route::match(['get', 'post'], 'payment-gateway/paypal/cancel', 'App\Plugins\Paypal\Controllers\ProcessController@cancel');
Route::match(['get', 'post'], 'payment-gateway/paypal/notify', 'App\Plugins\Paypal\Controllers\ProcessController@notify');
