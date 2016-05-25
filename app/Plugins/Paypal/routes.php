<?php

\Event::listen('App\Events\PaymentGateway', function ($event) {
    $controller = new App\Plugins\Paypal\Controllers\ProcessController();
    echo $controller->PassToPayment($event->para);
});
Route::get('payment-gateway/paypal', 'App\Plugins\Paypal\Controllers\SettingsController@Settings');
Route::patch('payment-gateway/paypal', 'App\Plugins\Paypal\Controllers\SettingsController@postSettings');
