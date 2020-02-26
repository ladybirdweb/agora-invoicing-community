<?php
Event::listen('App\Events\PaymentGateway', function($event){
	$controller = new App\Plugins\Stripe\Controllers\ProcessController();
	echo $controller->PassToPayment($event->para);
});
Route::get('payment-gateway/stripe', 'App\Plugins\Stripe\Controllers\SettingsController@Settings');
Route::patch('payment-gateway/stripe', 'App\Plugins\Stripe\Controllers\SettingsController@postSettings');
Route::post('change-base-currency/payment-gateway/stripe', 'App\Plugins\Stripe\Controllers\SettingsController@changeBaseCurrency');
Route::get('update-api-key/payment-gateway/stripe', 'App\Plugins\Stripe\Controllers\SettingsController@updateApiKey');
Route::post('stripe', 'App\Plugins\Stripe\Controllers\SettingsController@stripePost')->name('stripe.post');