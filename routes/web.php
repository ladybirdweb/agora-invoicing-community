<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
// 	dd('ok');
//     return view('welcome');
// });
// Route::group(['middleware' => ['web']], function () {
        // Route::get('/', 'HomeController@index');

        /*
         * Front end
         */

        Route::match(['get', 'post'], 'home', 'Front\CartController@productList');

        Route::get('pricing', 'Front\CartController@cart');
        Route::get('cart/remove', 'Front\CartController@cartRemove');
        Route::get('update-qty', 'Front\CartController@updateQty');
        Route::get('cart/addon/{id}', 'Front\CartController@addAddons');
        Route::get('cart/clear', 'Front\CartController@clearCart');
        Route::get('show/cart', 'Front\CartController@showCart');

        Route::get('checkout', 'Front\CheckoutController@checkoutForm');
        //Route::get('checkout', 'Front\CheckoutController@CheckoutForm');
        Route::match(['post', 'patch'], 'checkout', 'Front\CheckoutController@postCheckout');

        Route::get('ping', 'Front\CheckoutController@PingRecieve');
        Route::post('pricing/update', 'Front\CartController@addCouponUpdate');
        //Route::get('mail-chimp','Common\MailChimpController@getList');
        Route::get('mail-chimp/subcribe', 'Common\MailChimpController@addSubscriberByClientPanel');
        Route::get('mail-chimp/merge-fields', 'Common\MailChimpController@addFieldsToAgora');
        Route::get('mail-chimp/add-lists', 'Common\MailChimpController@addListsToAgora');
        Route::get('mailchimp', 'Common\MailChimpController@mailChimpSettings');
        Route::patch('mailchimp', 'Common\MailChimpController@postMailChimpSettings');
        Route::get('mail-chimp/mapping', 'Common\MailChimpController@mapField');
        Route::patch('mail-chimp/mapping', 'Common\MailChimpController@postMapField');
        Route::get('contact-us', 'Front\CartController@contactUs');
        Route::post('contact-us', 'Front\CartController@postContactUs');
        Route::get('add-cart/{slug}', 'Front\CartController@addCartBySlug');

        /*
         * Front Client Pages
         */

        Route::get('my-invoices', 'Front\ClientController@invoices');

        Route::get('get-my-invoices', 'Front\ClientController@getInvoices')->name('get-my-invoices');
        Route::get('get-my-invoices/{orderid}/{userid}', 'Front\ClientController@getInvoicesByOrderId');

        // Route::get('get-my-invoices/{orderid}/{userid}', ['uses' => 'Front\ClientController@getInvoicesByOrderId', 'as' => 'get-my-invoices']);

        Route::get('get-my-payment/{orderid}/{userid}', ['uses' => 'Front\ClientController@getPaymentByOrderId', 'as' => 'get-my-payment']);

        // Route::get('get-my-payment/{orderid}/{userid}', 'Front\ClientController@getPaymentByOrderId');
        // Route::get('get-my-payment-client/{orderid}/{userid}', 'Front\ClientController@getPaymentByOrderIdClient');

        Route::get('get-my-payment-client/{orderid}/{userid}', ['uses' => 'Front\ClientController@getPaymentByOrderIdClient', 'as' => 'get-my-payment-client']);

        Route::get('my-orders', 'Front\ClientController@orders');
        Route::get('get-my-orders', 'Front\ClientController@getOrders')->name('get-my-orders');
        Route::get('my-subscriptions', 'Front\ClientController@subscriptions');
        Route::get('get-my-subscriptions', 'Front\ClientController@getSubscriptions');
        Route::get('my-invoice/{id}', 'Front\ClientController@getInvoice');
        Route::get('my-order/{id}', 'Front\ClientController@getOrder');
        Route::get('my-subscription/{id}', 'Front\ClientController@getSubscription');
        Route::get('my-profile', 'Front\ClientController@profile');
        Route::patch('my-profile', 'Front\ClientController@postProfile');
        Route::patch('my-password', 'Front\ClientController@postPassword');
        Route::get('paynow/{id}', 'Front\CheckoutController@paynow');

        /*
         * Social Media
         */

        Route::resource('social-media', 'Common\SocialMediaController');
        Route::get('get-social-media', ['as' => 'get-social-media', 'uses' => 'Common\SocialMediaController@getSocials']);
        // Route::get('get-social-media', 'Common\SocialMediaController@getSocials');
        Route::get('social-media-delete', 'Common\SocialMediaController@destroy');
        /*
         * Tweeter api
         */
        Route::get('twitter', 'Common\SocialMediaController@getTweets');

        /*
         * Authentication
         */
    //     Route::get([
    // // 'auth'     => 'Auth\AuthController',
    // // 'password' => 'Auth\PasswordController',
    //     ]);
        Route::auth();
        Route::get('/', 'HomeController@index');
        Route::get('resend/activation/{email}', 'Auth\AuthController@sendActivationByGet');

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
        Route::get('settings', 'Common\SettingsController@settings');
        Route::get('settings/system', 'Common\SettingsController@settingsSystem');
        Route::patch('settings/system', 'Common\SettingsController@postSettingsSystem');
        Route::get('settings/email', 'Common\SettingsController@settingsEmail');
        Route::patch('settings/email', 'Common\SettingsController@postSettingsEmail');
        Route::get('settings/template', 'Common\SettingsController@settingsTemplate');
        Route::patch('settings/template', 'Common\SettingsController@postSettingsTemplate');
        Route::get('settings/error', 'Common\SettingsController@settingsError');
        Route::patch('settings/error', 'Common\SettingsController@postSettingsError');

        /*
         * Client
         */

        Route::resource('clients', 'User\ClientController');

        // Route::get('get-clients', 'User\ClientController@GetClients');
         Route::get('get-clients', ['as' => 'get-clients', 'uses' => 'User\ClientController@GetClients']);
        Route::get('clients-delete/{$id}', 'User\ClientController@destroy');
        Route::get('get-users', 'User\ClientController@getUsers');

        /*
         * Product
         */

        Route::resource('products', 'Product\ProductController');
         Route::get('get-products', ['as' => 'get-products', 'uses' => 'Product\ProductController@getProducts']);

        // Route::get('get-products', 'Product\ProductController@GetProducts');
        Route::get('products-delete', 'Product\ProductController@destroy');
        Route::post('get-price', 'Product\ProductController@getPrice');
        Route::post('get-product-field', 'Product\ProductController@getProductField');
        Route::get('get-subscription/{id}', 'Product\ProductController@getSubscriptionCheck');
        /*
         * Plan
         */

        Route::resource('plans', 'Product\PlanController');
         Route::get('get-plans', ['as' => 'get-plans', 'uses' => 'Product\PlanController@getPlans']);
        // Route::get('get-plans', 'Product\PlanController@GetPlans');
        Route::get('plans-delete', 'Product\PlanController@destroy');

        /*
         * Addons


          Route::resource('addons','Product\AddonController');
          Route::get('get-addons','Product\AddonController@getAddons');
          Route::get('addons-delete','Product\AddonController@destroy');
         */
        /*
         * Services
         */

        Route::resource('services', 'Product\ServiceController');
        Route::get('get-services', 'Product\ServiceController@getServices');
        Route::get('services-delete', 'Product\ServiceController@destroy');

        /*
         * Currency
         */

        Route::resource('currency', 'Payment\CurrencyController');
         Route::get('get-currency/datatable', ['as' => 'get-currency.datatable', 'uses' => 'Payment\CurrencyController@getCurrency']);
        // Route::get('get-currency', 'Payment\CurrencyController@GetCurrency');
        Route::get('currency-delete', 'Payment\CurrencyController@destroy');

        /*
         * Tax
         */

        Route::resource('tax', 'Payment\TaxController');
        Route::get('get-state/{state}', 'Payment\TaxController@getState');
        Route::get('get-tax', ['as' => 'get-tax', 'uses' => 'Payment\TaxController@getTax']);

        // Route::get('get-tax', 'Payment\TaxController@GetTax');

        Route::get('tax-delete', 'Payment\TaxController@destroy');
        Route::patch('taxes/option', 'Payment\TaxController@options');
        Route::post('taxes/option', 'Payment\TaxController@options');

        /*
         * Promotion
         */

        Route::resource('promotions', 'Payment\PromotionController');
        Route::post('get-code', 'Payment\PromotionController@getCode');
        Route::get('get-promotions', 'Payment\PromotionController@getPromotion');
        Route::get('promotions-delete', 'Payment\PromotionController@destroy');

        /*
         * Bundle
         */

        Route::resource('bundles', 'Product\BundleController');
        Route::get('get-bundles', 'Product\BundleController@getBundles');
        Route::get('bundles-delete', 'Product\BundleController@destroy');

        /*
         * Order
         */

        Route::resource('orders', 'Order\OrderController');
        // Route::get('get-orders', ['as' => 'get-orders', 'uses' => 'Order\OrderController@getOrders']);
        Route::get('get-orders', 'Order\OrderController@getOrders');
        Route::get('orders-delete', 'Order\OrderController@destroy');
        Route::get('order/execute', 'Order\OrderController@orderExecute');
        Route::get('change-domain', 'Order\OrderController@domainChange');
        Route::get('orders/{id}/delete', 'Order\OrderController@deleleById');

        /*
         * Groups
         */

        Route::resource('groups', 'Product\GroupController');
        Route::get('get-groups', 'Product\GroupController@getGroups')->name('get-groups');
        Route::get('groups-delete', 'Product\GroupController@destroy');

        /*
         * Templates
         */

        Route::resource('templates', 'Common\TemplateController');
         Route::get('get-templates', ['as' => 'get-templates', 'uses' => 'Common\TemplateController@getTemplates']);
        // Route::get('get-templates', 'Common\TemplateController@GetTemplates');
        Route::get('templates-delete', 'Common\TemplateController@destroy');
        Route::get('testmail/{id}', 'Common\TemplateController@mailtest');
        Route::get('testcart', 'Common\TemplateController@cartesting');

        /*
         * Invoices
         */

        Route::get('invoices', 'Order\InvoiceController@index');
        Route::get('invoices/{id}', 'Order\InvoiceController@show');
        Route::get('get-invoices', ['as' => 'get-invoices', 'uses' => 'Order\InvoiceController@getInvoices']);
        // Route::get('get-invoices', 'Order\InvoiceController@GetInvoices');
        Route::get('pdf', 'Order\InvoiceController@pdf');
        Route::get('invoice-delete', 'Order\InvoiceController@destroy');
        Route::get('invoice/generate', 'Order\InvoiceController@generateById');
        Route::post('generate/invoice/{user_id?}', 'Order\InvoiceController@invoiceGenerateByForm');
        Route::get('invoices/{id}/delete', 'Order\InvoiceController@deleleById');

        /*
         * Payment
         */

        Route::get('payment/receive', 'Order\InvoiceController@payment');
        Route::post('payment/receive/{id}', 'Order\InvoiceController@postPayment');
        Route::get('payment-delete', 'Order\InvoiceController@deletePayment');
        Route::get('payments/{id}/delete', 'Order\InvoiceController@paymentDeleleById');

        /*
         * Subscriptions
         */
        Route::get('subscriptions', 'Order\SubscriptionController@index');
        Route::get('subscriptions/{id}', 'Order\SubscriptionController@show');
        Route::get('get-subscriptions', 'Order\SubscriptionController@getSubscription');

        /*
         * Licences
         */
        Route::resource('licences', 'Licence\LicenceController');
        Route::get('get-licences', 'Licence\LicenceController@getLicences');

        /*
         * Slas
         */
        Route::resource('slas', 'Licence\SlaController');
        Route::get('get-slas', 'Licence\SlaController@getSlas');

        /*
         * Services
         */

        Route::resource('services', 'Licence\ServiceController');
        Route::get('get-services', 'Licence\ServiceController@getServices');

        /*
         * Pages
         */
        Route::resource('pages', 'Front\PageController');
        Route::get('pages/{slug}', 'Front\PageController@show');
        Route::get('page/search', 'Front\PageController@search');
        Route::get('get-pages', ['as' => 'get-pages', 'uses' => 'Front\PageController@getPages']);
        // Route::get('get-pages', 'Front\PageController@GetPages');
        Route::get('pages-delete', 'Front\PageController@destroy');
        Route::get('get-url', 'Front\PageController@generate');

        /*
         * Widgets
         */
        Route::resource('widgets', 'Front\WidgetController');
        Route::get('get-widgets', ['as' => 'get-widgets', 'uses' => 'Front\WidgetController@getPages']);
        // Route::get('get-widgets', 'Front\WidgetController@GetPages');
        Route::get('widgets-delete', 'Front\WidgetController@destroy');

        /*
         * github
         */
        Route::get('github-auth', 'Github\GithubController@authenticate');
        Route::get('github-auth-app', 'Github\GithubController@authForSpecificApp');
        Route::get('github-releases', 'Github\GithubController@listRepositories');
        Route::get('github-one-release', 'Github\GithubController@getReleaseByTag');
        Route::get('github-downloads', 'Github\GithubController@getDownloadCount');
        Route::get('github', 'Github\GithubController@getSettings');
        Route::patch('github', 'Github\GithubController@postSettings');

        /*
         * download
         */
        Route::get('download/{userid}/{invoice_number}', 'Product\ProductController@userDownload');
        Route::get('product/download/{id}', 'Product\ProductController@adminDownload');

        /*
         * testings
         */
        Route::get('test-curl', 'Github\GithubApiController@testCurl');
        Route::get('test-curl-result', 'Github\GithubApiController@testCurlResult');

        /*
         * check version
         */

        Route::post('version', 'HomeController@version');
        Route::get('version', 'HomeController@getVersion');
        Route::get('version-test', 'HomeController@versionTest');
        Route::post('version-result', 'HomeController@versionResult');
        Route::post('verification', 'HomeController@faveoVerification');
        Route::post('download-url', 'Github\GithubController@getlatestReleaseForUpdate');
        Route::get('create-keys', 'HomeController@createEncryptionKeys');
        Route::get('encryption', 'HomeController@getEncryptedData');

        Route::post('faveo-hook', 'HomeController@hook');

        /*
         * plugins
         */
        Route::get('plugin', 'Common\SettingsController@plugins');

         Route::get('get-plugin', ['as' => 'get-plugin', 'uses' => 'Common\SettingsController@getPlugin']);
        // Route::get('getplugin', 'Common\SettingsController@getPlugin');
        Route::post('post-plugin', ['as' => 'post.plugin', 'uses' => 'Common\SettingsController@postPlugins']);
        Route::get('plugin/delete/{slug}', ['as' => 'delete.plugin', 'uses' => 'Common\SettingsController@deletePlugin']);
        Route::get('plugin/status/{slug}', ['as' => 'status.plugin', 'uses' => 'Common\SettingsController@statusPlugin']);

        /*
         * Cron Jobs
         */
        Route::get('expired-subscriptions', 'Common\CronController@eachSubscription');

        /*
         * Renew
         */

        Route::get('renew/{id}', 'Order\RenewController@renewForm');
        Route::post('renew/{id}', 'Order\RenewController@renew');
        Route::post('get-renew-cost', 'Order\RenewController@getCost');
        Route::post('client/renew/{id}', 'Order\RenewController@renewByClient');

        Route::post('serial', 'HomeController@serial');
        Route::post('v2/serial', 'HomeController@serialV2');
        Route::get('generate-keys', 'HomeController@createEncryptionKeys');

        Route::get('get-code', 'WelcomeController@getCode');
        Route::get('get-currency', 'WelcomeController@getCurrency');

        Route::get('country-count', 'WelcomeController@countryCount');

        /*
         * Api
         */
        Route::group(['prefix' => 'api'], function () {
            /*
             * Unautherised requests
             */
            Route::get('check-url', 'Api\ApiController@checkDomain');
        });

        Route::get('otp/send', 'Auth\AuthController@requestOtp');
        Route::get('otp/sendByAjax', 'Auth\AuthController@requestOtpFromAjax');
        Route::get('otp/verify', 'Auth\AuthController@postOtp');
        Route::get('email/verify', 'Auth\AuthController@verifyEmail');
        Route::get('resend_otp', 'Auth\AuthController@retryOTP');
        Route::get('verify', function () {
            $user = \Session::get('user');
            if ($user) {
                return view('themes.default1.user.verify', compact('user'));
            }

            return redirect('auth/login');
        });

        Route::post('download/faveo', 'HomeController@downloadForFaveo');
        Route::get('version/latest', 'HomeController@latestVersion');
    // });
