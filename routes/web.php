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
//  dd('ok');
//     return view('welcome');
// });
// Route::group(['middleware' => ['web']], function () {
        // Route::get('/', 'HomeController@index');

        /*
         * Front end
         */

        // Route::match(['get', 'post'], 'home', 'Front\CartController@productList');
        // VisitStats::routes();
        Route::get('pricing', 'Front\CartController@cart')->name('pricing');
        Route::get('group/{templateid}/{groupid}/', 'Front\PageController@pageTemplates');
        Route::get('cart/remove', 'Front\CartController@cartRemove');
        Route::get('update-agent-qty', 'Front\CartController@updateAgentQty');
        Route::get('update-qty', 'Front\CartController@updateProductQty');
        Route::get('reduce-product-qty', 'Front\CartController@reduceProductQty');
        Route::get('reduce-agent-qty', 'Front\CartController@reduceAgentQty');
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
        Route::patch('mailchimp-ispaid/mapping', 'Common\MailChimpController@postIsPaidMapField');
        Route::patch('mailchimp-group/mapping', 'Common\MailChimpController@postGroupMapField');
        Route::get('get-group-field/{value}', 'Common\MailChimpController@addInterestFieldsToAgora');
        Route::get('contact-us', 'Front\CartController@contactUs');
        Route::post('contact-us', 'Front\CartController@postContactUs');

        /*
         * Front Client Pages
         */

        Route::get('my-invoices', 'Front\ClientController@invoices')->name('my-invoices');

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
        Route::get('paynow/{id}', 'Front\CheckoutController@payNow');

        Route::get('get-versions/{productid}/{clientid}/{invoiceid}/', ['as' => 'get-versions', 'uses' => 'Front\ClientController@getVersionList']);
        Route::get('get-github-versions/{productid}/{clientid}/{invoiceid}/', ['as' => 'get-github-versions', 'uses' => 'Front\ClientController@getGithubVersionList']);

        // Get Route For Show Razorpay Payment Form
        Route::get('paywithrazorpay', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
        // Post Route For Make Razorpay Payment Request
        Route::post('payment/{invoice}', 'RazorpayController@payment')->name('payment');

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
        Route::get('twitter', 'Common\SocialMediaController@getTweets')->name('twitter');

        /*
         * Authentication
         */
    //     Route::get([
    // // 'auth'     => 'Auth\AuthController',
    // // 'password' => 'Auth\PasswordController',
    //     ]);
        Route::auth();
        Route::get('/', 'DashboardController@index');
        Route::get('resend/activation/{email}', 'Auth\AuthController@sendActivationByGet');

        Route::get('activate/{token}', 'Auth\AuthController@activate');

         Route::get('change/email', 'Auth\AuthController@updateUserEmail');

        /*
         * Profile Process
         */

        Route::get('profile', 'User\ProfileController@profile');
        Route::patch('profile', 'User\ProfileController@updateProfile');
        Route::patch('password', 'User\ProfileController@updatePassword');

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
        // Route::get('settings/error', 'Common\SettingsController@settingsError');
        // Route::get('/log-viewer', 'Common\SettingsController@viewLogs');
        Route::patch('settings/error', 'Common\SettingsController@postSettingsError');
        Route::get('settings/activitylog', 'Common\SettingsController@settingsActivity');
         Route::get('settings/maillog', 'Common\SettingsController@settingsMail');
         Route::get('get-activity', ['as' => 'get-activity', 'uses' => 'Common\SettingsController@getActivity']);
          Route::get('get-email', ['as' => 'get-email', 'uses' => 'Common\SettingsController@getMails']);
         Route::get('activity-delete', 'Common\SettingsController@destroy')->name('activity-delete');
          Route::get('email-delete', 'Common\SettingsController@destroyEmail')->name('email-delete');
           Route::get('licenseDetails', 'Common\BaseSettingsController@licenseDetails')->name('licenseDetails');
            Route::get('updateDetails', 'Common\BaseSettingsController@updateDetails')->name('updateDetails');
            Route::get('captchaDetails', 'Common\BaseSettingsController@captchaDetails')->name('captchaDetails');
            Route::get('updatemobileDetails', 'Common\BaseSettingsController@updateMobileDetails')->name('updatemobileDetails');
            Route::get('updateemailDetails', 'Common\BaseSettingsController@updateEmailDetails')->name('updateemailDetails');
            Route::get('updatetwitterDetails', 'Common\BaseSettingsController@updateTwitterDetails')->name('updatetwitterDetails');
            Route::get('updateMailchimpDetails', 'Common\BaseSettingsController@updateMailchimpDetails')->name('updateMailchimpDetails');
             Route::get('updateTermsDetails', 'Common\BaseSettingsController@updateTermsDetails')->name('updateTermsDetails');
            Route::get('updaterzpDetails', 'Common\BaseSettingsController@updateRazorpayDetails')->name('updaterzpDetails');
             Route::get('updatezohoDetails', 'Common\BaseSettingsController@updateZohoDetails')->name('updatezohoDetails');
             Route::get('updatepipedriveDetails', 'Common\BaseSettingsController@updatepipedriveDetails')->name('updatepipedriveDetails');
              Route::get('mailchimp-prod-status', 'Common\BaseSettingsController@updateMailchimpProductStatus')->name('mailchimp-prod-status');
               Route::get('mailchimp-paid-status', 'Common\BaseSettingsController@updateMailchimpIsPaidStatus')->name('mailchimp-paid-status');
               Route::get('updatedomainCheckDetails', 'Common\BaseSettingsController@updatedomainCheckDetails')->name('updatedomainCheckDetails');
            Route::get('system-managers', 'Common\SystemManagerController@getSystemManagers')->name('system-managers');
            Route::get('search-admins', 'Common\SystemManagerController@searchAdmin')->name('search-admins');
            Route::post('replace-acc-manager', 'Common\SystemManagerController@replaceAccountManager')->name('replace-acc-manager');
            Route::post('replace-sales-manager', 'Common\SystemManagerController@replaceSalesManager')->name('replace-sales-manager');

        /*
         * Client
         */

        Route::resource('clients', 'User\ClientController');
         Route::get('getClientDetail/{id}', 'User\ClientController@getClientDetail');
          Route::get('getPaymentDetail/{id}', 'User\ClientController@getPaymentDetail');
          Route::get('getOrderDetail/{id}', 'User\ClientController@getOrderDetail');
        // Route::get('get-clients', 'User\ClientController@GetClients');
         Route::get('get-clients', ['as' => 'get-clients', 'uses' => 'User\ClientController@getClients']);
        Route::get('clients-delete', 'User\ClientController@destroy');
        Route::get('get-users', 'User\ClientController@getUsers');
         Route::get('search-email', 'User\ClientController@search')->name('search-email');

        /*
         * Product
         */

        Route::resource('products', 'Product\ProductController');
        Route::get('get-products', ['as' => 'get-products', 'uses' => 'Product\ProductController@getProducts']);
        // Route::get('get-products', 'Product\ProductController@GetProducts');
        Route::get('products-delete', 'Product\ProductController@destroy')->name('products-delete');
        Route::get('uploads-delete', 'Product\ProductController@fileDestroy')->name('uploads-delete');

        Route::post('get-price', 'Product\ProductController@getPrice');
        Route::post('get-product-field', 'Product\ProductController@getProductField');
        Route::get('get-subscription/{id}', 'Product\ProductController@getSubscriptionCheck');
        Route::get('edit-upload/{id}', 'Product\ProductController@editProductUpload');
        Route::get('get-upload/{id}', 'Product\ProductController@getUpload')->name('get-upload');
        Route::post('upload/save', 'Product\ProductController@save')->name('upload/save');
        Route::post('chunkupload', 'Product\ProductController@uploadFile');
        Route::patch('upload/{id}', 'Product\ProductController@uploadUpdate');
        Route::get('get-group-url', 'Product\GroupController@generateGroupUrl');

        /*
         * Plan
         */

        Route::resource('plans', 'Product\PlanController');
         Route::get('get-plans', ['as' => 'get-plans', 'uses' => 'Product\PlanController@getPlans']);
        // Route::get('get-plans', 'Product\PlanController@GetPlans');
        Route::get('plans-delete', 'Product\PlanController@destroy')->name('plans-delete');
        Route::get('get-period', 'Product\PlanController@checkSubscription')->name('get-period');
        Route::post('postInsertPeriod', 'Product\PlanController@postInsertPeriod');

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
        Route::get('currency-delete', 'Payment\CurrencyController@destroy')->name('currency-delete');

          Route::post('change/currency/status', ['as' => 'change.currency.status', 'uses' => 'Payment\CurrencyController@updatecurrency']);
        Route::get('dashboard-currency/{id}', 'Payment\CurrencyController@setDashboardCurrency');

        /*
         * Tax
         */

        Route::resource('tax', 'Payment\TaxController');
        Route::get('get-state/{state}', 'Payment\TaxController@getState');
        Route::get('get-tax', ['as' => 'get-tax', 'uses' => 'Payment\TaxController@getTax']);
        Route::get('get-loginstate/{state}', 'Auth\AuthController@getState');

        Route::get('get-taxtable', ['as' => 'get-taxtable', 'uses' => 'Payment\TaxController@getTaxTable']);
        Route::get('get-loginstate/{state}', 'Auth\AuthController@getState');

        // Route::get('get-tax', 'Payment\TaxController@GetTax');

        Route::get('tax-delete', 'Payment\TaxController@destroy')->name('tax-delete');
        Route::patch('taxes/option', 'Payment\TaxController@options')->name('taxes/option');
        Route::post('taxes/option', 'Payment\TaxController@options');

        /*
         * Promotion
         */

        Route::resource('promotions', 'Payment\PromotionController');

        Route::post('get-code', 'Payment\PromotionController@getCode')->name('get-code');
        Route::get('get-promotions', 'Payment\PromotionController@getPromotion')->name('get-promotions');
        Route::get('promotions-delete', 'Payment\PromotionController@destroy')->name('promotions-delete');



        /*
         * Category
         */

         Route::resource('category', 'Product\CategoryController');
         Route::get('get-category', 'Product\CategoryController@getCategory')->name('get-category');
         Route::get('category-delete', 'Product\CategoryController@destroy')->name('category-delete');

        /*
         * Comment
         */
         Route::resource('comment', 'User\CommentController');
         Route::get('comment/{id}/delete', 'User\CommentController@destroy');

         /*
         * Product-type
         */
         Route::resource('product-type', 'Product\ProductTypeController');
         Route::get('get-type', 'Product\ProductTypeController@getTypes')->name('get-type');
         Route::get('type-delete', 'Product\ProductTypeController@destroy')->name('type-delete');

          /*
         * License
         */
         Route::resource('license-type', 'License\LicenseSettingsController');
         Route::get('get-license-type', 'License\LicenseSettingsController@getLicenseTypes')->name('get-license-type');
         Route::get('license-type-delete', 'License\LicenseSettingsController@destroy')->name('license-type-delete');
         Route::get('license-permissions', 'License\LicensePermissionsController@index');
         Route::get('get-license-permission', 'License\LicensePermissionsController@getPermissions')->name('get-license-permission');
         Route::get('add-permission', 'License\LicensePermissionsController@addPermission')->name('add-permission');
         Route::get('tick-permission', 'License\LicensePermissionsController@tickPermission')->name('tick-permission');
        /*
         * Order
         */

        // Route::resource('orders', 'Order\change-domain');
         // Route::get('get-orders', ['as' => 'get-orders', 'uses' => 'Order\change-domain@getOrders'])->name('get-orders');
          Route::resource('orders', 'Order\OrderController');
         // Route::get('get-orders', ['as' => 'get-orders', 'uses' => 'Order\OrderController@getOrders'])->name('get-orders');
        Route::get('get-orders', 'Order\OrderController@getOrders')->name('get-orders');
        Route::get('orders-delete', 'Order\OrderController@destroy')->name('orders-delete');
        Route::get('order/execute', 'Order\OrderController@orderExecute');
        Route::patch('change-domain', 'Order\ExtendedOrderController@changeDomain');
        Route::patch('reissue-license', 'Order\ExtendedOrderController@reissueLicense');
        Route::get('orders/{id}/delete', 'Order\OrderController@deleleById');
        Route::get('edit-update-expiry', 'Order\BaseOrderController@editUpdateExpiry');
        Route::get('edit-license-expiry', 'Order\BaseOrderController@editLicenseExpiry');
        Route::get('edit-support-expiry', 'Order\BaseOrderController@editSupportExpiry');
        Route::get('edit-installation-limit', 'Order\BaseOrderController@editInstallationLimit');
        Route::post('ip-or-domain', 'Order\BaseOrderController@installOnIpOrDomain');
        /*
         * Groups
         */

        Route::resource('groups', 'Product\GroupController');
        Route::get('get-groups', 'Product\GroupController@getGroups')->name('get-groups');
        Route::get('groups-delete', 'Product\GroupController@destroy')->name('groups-delete');

        /*
         * Templates
         */

        Route::resource('templates', 'Common\TemplateController');
         Route::get('get-templates', ['as' => 'get-templates', 'uses' => 'Common\TemplateController@getTemplates']);
        // Route::get('get-templates', 'Common\TemplateController@GetTemplates');
        Route::get('templates-delete', 'Common\TemplateController@destroy')->name('templates-delete');
        Route::get('testmail/{id}', 'Common\TemplateController@mailtest');
        Route::get('testcart', 'Common\TemplateController@cartesting');

        /*
         * Chat Script
         */
         Route::resource('chat', 'Common\ChatScriptController');
          Route::get('get-script', ['as' => 'get-script', 'uses' => 'Common\ChatScriptController@getScript']);
          Route::get('script-delete', 'Common\ChatScriptController@destroy')->name('script-delete');
        /*
         * Invoices
         */

        Route::get('invoices', 'Order\InvoiceController@index');
        Route::get('invoices/{id}', 'Order\InvoiceController@show');
         Route::get('get-client-invoice/{id}', 'User\ClientController@getClientInvoice');
        Route::get('invoices/edit/{id}', 'Order\InvoiceController@edit');
         Route::post('invoice/edit/{id}', 'Order\InvoiceController@postEdit');
        Route::get('get-invoices', ['as' => 'get-invoices', 'uses' => 'Order\InvoiceController@getInvoices']);
        // Route::get('get-invoices', 'Order\InvoiceController@GetInvoices');
        Route::get('pdf', 'Order\InvoiceController@pdf');
        Route::get('invoice-delete', 'Order\InvoiceController@destroy')->name('invoice-delete');
        Route::get('invoice/generate', 'Order\InvoiceController@generateById');
        Route::post('generate/invoice/{user_id?}', 'Order\InvoiceController@invoiceGenerateByForm');
        Route::get('invoices/{id}/delete', 'Order\InvoiceController@deleleById');

        Route::get('change-invoiceTotal', ['as' => 'change-invoiceTotal',
            'uses'                              => 'Order\InvoiceController@invoiceTotalChange', ]);
        Route::get('change-paymentTotal', ['as' => 'change-paymentTotal',
            'uses'                              => 'Order\InvoiceController@paymentTotalChange', ]);

        /*
         * Payment
         */
        Route::get('newPayment/receive', 'Order\InvoiceController@newPayment');
        Route::post('newPayment/receive/{clientid}', 'Order\InvoiceController@postNewPayment');
        Route::get('payment/receive', 'Order\InvoiceController@payment');
        Route::post('payment/receive/{id}', 'Order\InvoiceController@postPayment');
        Route::get('payment-delete', 'Order\InvoiceController@deletePayment')->name('payment-delete');
        Route::get('payments/{id}/delete', 'Order\InvoiceController@paymentDeleleById');
        Route::get('payments/{payment_id}/edit', 'Order\InvoiceController@paymentEditById');
        Route::post('newMultiplePayment/receive/{clientid}', 'Order\InvoiceController@postNewMultiplePayment');
         Route::post('newMultiplePayment/update/{clientid}', 'Order\InvoiceController@updateNewMultiplePayment');

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
         * Pages
         */
        Route::resource('pages', 'Front\PageController');
        Route::get('pages/{slug}', 'Front\PageController@show');
        Route::get('page/search', 'Front\PageController@search');
        Route::get('get-pages', ['as' => 'get-pages', 'uses' => 'Front\PageController@getPages']);
        // Route::get('get-pages', 'Front\PageController@GetPages');
        Route::get('pages-delete', 'Front\PageController@destroy')->name('pages-delete');
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
        Route::get('github-setting', 'Github\GithubController@postSettings');

        /*
         * download
         */
        Route::get('download/{uploadid}/{userid}/{invoice_number}/{versionid}', 'Product\ProductController@userDownload');
        Route::get('product/download/{id}/{invoice?}', 'Product\ProductController@adminDownload');


        /*
         * check version
         */

        Route::post('version', 'HomeController@version');
        Route::get('version', 'HomeController@getVersion');
        Route::post('verification', 'HomeController@faveoVerification');
        Route::post('download-url', 'Github\GithubController@getlatestReleaseForUpdate');
        Route::get('create-keys', 'HomeController@createEncryptionKeys');
        Route::get('encryption', 'HomeController@getEncryptedData');

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

        Route::get('job-scheduler', ['as'=>'get.job.scheduler', 'uses'=>'Common\SettingsController@getScheduler']);
         Route::patch('post-scheduler', ['as' => 'post.job.scheduler', 'uses' => 'Common\SettingsController@postSchedular'])->name('post-scheduler'); //to update job scheduler
         Route::patch('cron-days', ['as'=>'cron-days', 'uses'=>'Common\SettingsController@saveCronDays'])->name('cron-days');
         Route::post('verify-php-path', ['as' => 'verify-cron', 'uses' => 'Common\SettingsController@checkPHPExecutablePath']);
        Route::get('file-storage', 'Common\SettingsController@showFileStorage');
         Route::post('file-storage-path', 'Common\SettingsController@updateStoragePath');
        Route::get('expired-subscriptions', 'Common\CronController@eachSubscription');

        /*
         * Renew
         */

        Route::get('renew/{id}', 'Order\RenewController@renewForm');
        Route::post('renew/{id}', 'Order\RenewController@renew');
        Route::post('get-renew-cost', 'Order\RenewController@getCost');
        Route::post('client/renew/{id}', 'Order\RenewController@renewByClient');

        Route::get('generate-keys', 'HomeController@createEncryptionKeys');

        Route::get('get-code', 'WelcomeController@getCode');
        Route::get('get-currency', 'WelcomeController@getCurrency');
         Route::get('get-country', 'WelcomeController@getCountry');
        Route::get('country-count', 'WelcomeController@countryCount')->name('country-count');

        /*
         * Api
         */
        Route::group(['prefix' => 'api'], function () {
            /*
             * Unautherised requests
             */
            Route::get('check-url', 'Api\ApiController@checkDomain');
        });

        /*
         * Api Keys
         */
        Route::get('apikeys', 'Common\SettingsController@getKeys');
        Route::patch('apikeys', 'Common\SettingsController@postKeys');

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
         /*
         * Faveo APIs
         */
        Route::post('serial', 'HomeController@serial');
        Route::post('v2/serial', 'HomeController@serialV2');
        Route::get('download/faveo', 'HomeController@downloadForFaveo');
        Route::get('version/latest', 'HomeController@latestVersion');
        Route::post('update-latest-version', 'HomeController@updateLatestVersion');
        Route::post('v1/checkUpdatesExpiry', 'HomeController@checkUpdatesExpiry');

        Route::get('404', ['as' => 'error404', function () {
            return view('errors.404');
        }]);
    // });
