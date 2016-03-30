<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

/*
 * Front end
 */

Route::match(['get','post'],'home', 'Front\CartController@ProductList');
Route::get('pricing', 'Front\CartController@Cart');
Route::get('cart/remove', 'Front\CartController@CartRemove');
Route::get('cart/reduseqty', 'Front\CartController@ReduseQty');
Route::get('cart/increaseqty', 'Front\CartController@IncreaseQty');
Route::get('cart/addon/{id}', 'Front\CartController@AddAddons');
Route::get('cart/clear', 'Front\CartController@ClearCart');

Route::get('checkout', 'Front\CheckoutController@CheckoutForm');
//Route::get('checkout', 'Front\CheckoutController@CheckoutForm');
Route::match(['post', 'patch'], 'checkout', 'Front\CheckoutController@postCheckout');

Route::get('ping', 'Front\CheckoutController@PingRecieve');

/*
 * Authentication
 */
Route::controllers([
    'auth'     => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::get('activate/{token}', 'Auth\AuthController@Activate');

/*
 * Profile Process
 */

Route::get('profile', 'User\ProfileController@Profile');
Route::patch('profile', 'User\ProfileController@UpdateProfile');
Route::patch('password', 'User\ProfileController@UpdatePassword');

/*
 * Settings
 */

Route::get('settings', 'Common\SettingsController@Settings');
Route::patch('settings', 'Common\SettingsController@UpdateSettings');

/*
 * Client
 */

Route::resource('clients', 'User\ClientController');
Route::get('get-clients', 'User\ClientController@GetClients');
Route::get('clients-delete', 'User\ClientController@destroy');

/*
 * Product
 */

Route::resource('products', 'Product\ProductController');
Route::get('get-products', 'Product\ProductController@GetProducts');
Route::get('products-delete', 'Product\ProductController@destroy');

/*
 * Plan
 */

Route::resource('plans', 'Product\PlanController');
Route::get('get-plans', 'Product\PlanController@GetPlans');
Route::get('plans-delete', 'Product\PlanController@destroy');

/*
 * Addons


Route::resource('addons','Product\AddonController');
Route::get('get-addons','Product\AddonController@GetAddons');
Route::get('addons-delete','Product\AddonController@destroy');
*/
/*
 * Services
 */

Route::resource('services', 'Product\ServiceController');
Route::get('get-services', 'Product\ServiceController@GetServices');
Route::get('services-delete', 'Product\ServiceController@destroy');

/*
 * Currency
 */

Route::resource('currency', 'Payment\CurrencyController');
Route::get('get-currency', 'Payment\CurrencyController@GetCurrency');
Route::get('currency-delete', 'Payment\CurrencyController@destroy');

/*
 * Tax
 */

Route::resource('tax', 'Payment\TaxController');
Route::patch('tax-rule', 'Payment\TaxController@Rule');
Route::post('get-state', 'Payment\TaxController@GetState');
Route::get('get-tax', 'Payment\TaxController@GetTax');
Route::get('tax-delete', 'Payment\TaxController@destroy');

/*
 * Promotion
 */

Route::resource('promotions', 'Payment\PromotionController');
Route::post('get-code', 'Payment\PromotionController@GetCode');
Route::get('get-promotions', 'Payment\PromotionController@GetPromotion');
Route::get('promotions-delete', 'Payment\PromotionController@destroy');

/*
 * Bundle
 */

Route::resource('bundles', 'Product\BundleController');
Route::get('get-bundles', 'Product\BundleController@GetBundles');
Route::get('bundles-delete', 'Product\BundleController@destroy');

/*
 * Order
 */

Route::resource('orders', 'Order\OrderController');
Route::get('get-orders', 'Order\OrderController@GetOrders');
Route::get('orders-delete', 'Order\OrderController@destroy');
Route::get('order/execute', 'Order\OrderController@orderExecute');

/*
 * Groups
 */

Route::resource('groups', 'Product\GroupController');
Route::get('get-groups', 'Product\GroupController@GetGroups');
Route::get('groups-delete', 'Product\GroupController@destroy');

/*
 * Templates
 */

Route::resource('templates', 'Common\TemplateController');
Route::get('get-templates', 'Common\TemplateController@GetTemplates');
Route::get('templates-delete', 'Common\TemplateController@destroy');
Route::get('testmail/{id}', 'Common\TemplateController@mailtest');
Route::get('testcart', 'Common\TemplateController@cartesting');

/*
 * Invoices
 */

Route::get('invoices', 'Order\InvoiceController@index');
Route::get('invoices/{id}', 'Order\InvoiceController@show');
Route::get('get-invoices', 'Order\InvoiceController@GetInvoices');

Route::get('invoice/generate', 'Order\InvoiceController@generateById');

/*
 * Subscriptions
 */
Route::get('subscriptions', 'Order\SubscriptionController@index');
Route::get('subscriptions/{id}', 'Order\SubscriptionController@show');
Route::get('get-subscriptions', 'Order\SubscriptionController@GetSubscription');

/*
 * Licences
 */
Route::resource('licences', 'Licence\LicenceController');
Route::get('get-licences', 'Licence\LicenceController@GetLicences');

/*
 * Slas
 */
Route::resource('slas', 'Licence\SlaController');
Route::get('get-slas', 'Licence\SlaController@GetSlas');

/*
 * Services
 */

Route::resource('services', 'Licence\ServiceController');
Route::get('get-services', 'Licence\ServiceController@GetServices');

/*
 * Pages
 */
Route::resource('pages', 'Front\PageController');
Route::get('pages/{slug}', 'Front\PageController@show');
Route::get('page/search', 'Front\PageController@Search');
Route::get('get-pages', 'Front\PageController@GetPages');
Route::get('pages-delete', 'Front\PageController@destroy');
Route::get('get-url', 'Front\PageController@Generate');

/*
 * Widgets
 */
Route::resource('widgets', 'Front\WidgetController');
Route::get('get-widgets', 'Front\WidgetController@GetPages');
Route::get('widgets-delete', 'Front\WidgetController@destroy');

/*
 * github
 */
Route::get('github-auth','Github\GithubController@authenticate');
Route::get('github-auth-app','Github\GithubController@authForSpecificApp');
Route::get('github-releases','Github\GithubController@listRepositories');
Route::get('github-one-release','Github\GithubController@getReleaseByTag');
Route::get('github-downloads','Github\GithubController@getDownloadCount');
Route::get('github','Github\GithubController@getSettings');
Route::patch('github','Github\GithubController@postSettings');

/**
 * download
 */
Route::get('download/{userid}/{invoice_number}','Product\ProductController@userDownload');
