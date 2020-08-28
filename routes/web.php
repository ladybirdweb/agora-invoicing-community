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

        // VisitStats::routes();
        Route::get('refresh-csrf', function () {
            return response()->json([
                'token'=>csrf_token(), ],
              200);
        });

         /*
         * Installer Routes
         */

        Route::group(['prefix' => 'install', 'as' => 'AgoraInstaller::', 'middleware' => ['isInstalled']], function () {
            Route::get('/', [
                'as' => 'welcome',
                'uses' => 'Installer\WelcomeController@welcome',
            ]);

            Route::get('requirements', [
                'as' => 'requirements',
                'uses' => 'Installer\RequirementsController@requirements',
            ]);

            Route::get('permissions', [
                'as' => 'permissions',
                'uses' => 'Installer\PermissionsController@permissions',
            ]);

            Route::get('environment', [
                'as' => 'environment',
                'uses' => 'Installer\EnvironmentController@environmentMenu',
            ]);

            Route::get('environment/wizard', [
                'as' => 'environmentWizard',
                'uses' => 'Installer\EnvironmentController@environmentWizard',
            ]);

            Route::post('environment/saveWizard', [
                'as' => 'environmentSaveWizard',
                'uses' => 'Installer\EnvironmentController@saveWizard',
            ]);

            Route::get('database', [
                'as' => 'database',
                'uses' => 'Installer\DatabaseController@database',
            ]);

            Route::get('final', [
                'as' => 'final',
                'uses' => 'Installer\FinalController@finish',
            ]);
        });

        Route::group(['middleware' => ['install']], function () {
            Route::get('pricing', 'Front\CartController@cart')->name('pricing');
            Route::get('group/{templateid}/{groupid}/', 'Front\PageController@pageTemplates');
            Route::post('cart/remove', 'Front\CartController@cartRemove');
            Route::post('update-agent-qty', 'Front\CartController@updateAgentQty');
            Route::post('update-qty', 'Front\CartController@updateProductQty');
            Route::post('reduce-product-qty', 'Front\CartController@reduceProductQty');
            Route::post('reduce-agent-qty', 'Front\CartController@reduceAgentQty');
            Route::post('cart/clear', 'Front\CartController@clearCart');
            Route::get('show/cart', 'Front\CartController@showCart');

            Route::get('checkout', 'Front\CheckoutController@checkoutForm');
            Route::match(['post', 'patch'], 'checkout', 'Front\CheckoutController@postCheckout');

            Route::post('pricing/update', 'Front\CartController@addCouponUpdate');
            Route::post('update-final-price', 'Front\CartController@updateFinalPrice');
            Route::post('mail-chimp/subcribe', 'Common\MailChimpController@addSubscriberByClientPanel');
            Route::get('mailchimp', 'Common\MailChimpController@mailChimpSettings')->middleware('admin');
            Route::patch('mailchimp', 'Common\MailChimpController@postMailChimpSettings');
            Route::get('mail-chimp/mapping', 'Common\MailChimpController@mapField')->middleware('admin');
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
            Route::get('my-profile', 'Front\ClientController@profile');
            Route::patch('my-profile', 'Front\ClientController@postProfile');
            Route::patch('my-password', 'Front\ClientController@postPassword');
            Route::get('paynow/{id}', 'Front\CheckoutController@payNow');

            Route::get('get-versions/{productid}/{clientid}/{invoiceid}/', ['as' => 'get-versions', 'uses' => 'Front\ClientController@getVersionList']);
            Route::get('get-github-versions/{productid}/{clientid}/{invoiceid}/', ['as' => 'get-github-versions', 'uses' => 'Front\ClientController@getGithubVersionList']);

            // Post Route For Make Razorpay Payment Request
            Route::post('payment/{invoice}', 'RazorpayController@payment')->name('payment');

            /*
             * 2FA Routes
             */

            Route::post('/2fa/enable', 'Google2FAController@enableTwoFactor');
            Route::post('2fa/disable/{userId?}', 'Google2FAController@disableTwoFactor');
            Route::get('/2fa/validate', 'Google2FAController@getValidateToken');
            Route::get('verify-2fa', 'Google2FAController@verify2fa');
            Route::get('2fa/loginValidate', 'Google2FAController@postLoginValidateToken')->name('2fa/loginValidate');
            Route::post('2fa/setupValidate', 'Google2FAController@postSetupValidateToken');
            Route::get('verify-password', 'Google2FAController@verifyPassword');
            Route::post('2fa-recovery-code', 'Google2FAController@generateRecoveryCode');
            Route::get('get-recovery-code', 'Google2FAController@getRecoveryCode');
            Route::get('recovery-code', 'Google2FAController@showRecoveryCode');
            Route::post('verify-recovery-code', 'Google2FAController@verifyRecoveryCode')->name('verify-recovery-code');
            /*
             * Social Media
             */

            Route::resource('social-media', 'Common\SocialMediaController');
            Route::get('get-social-media', ['as' => 'get-social-media', 'uses' => 'Common\SocialMediaController@getSocials']);
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
            Route::post('auth/register', 'Auth\RegisterController@postRegister')->name('auth/register');
            Route::get('auth/logout', 'Auth\LoginController@logout')->name('logout');
            Route::get('/', 'DashboardController@index');

            Route::get('activate/{token}', 'Auth\AuthController@activate');

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
            Route::patch('settings/error', 'Common\SettingsController@postSettingsError');
            Route::get('settings/activitylog', 'Common\SettingsController@settingsActivity');
            Route::get('settings/maillog', 'Common\SettingsController@settingsMail');
            Route::get('get-activity', ['as' => 'get-activity', 'uses' => 'Common\SettingsController@getActivity']);
            Route::get('get-email', ['as' => 'get-email', 'uses' => 'Common\SettingsController@getMails']);
            Route::delete('activity-delete', 'Common\SettingsController@destroy')->name('activity-delete');
            Route::delete('email-delete', 'Common\SettingsController@destroyEmail')->name('email-delete');
            Route::post('licenseDetails', 'Common\BaseSettingsController@licenseDetails')->name('licenseDetails');
            Route::post('updateDetails', 'Common\BaseSettingsController@updateDetails')->name('updateDetails');
            Route::post('captchaDetails', 'Common\BaseSettingsController@captchaDetails')->name('captchaDetails');
            Route::post('updatemobileDetails', 'Common\BaseSettingsController@updateMobileDetails')->name('updatemobileDetails');
            Route::post('updateemailDetails', 'Common\BaseSettingsController@updateEmailDetails')->name('updateemailDetails');
            Route::post('updatetwitterDetails', 'Common\BaseSettingsController@updateTwitterDetails')->name('updatetwitterDetails');
            Route::post('updateMailchimpDetails', 'Common\BaseSettingsController@updateMailchimpDetails')->name('updateMailchimpDetails');
            Route::post('updateTermsDetails', 'Common\BaseSettingsController@updateTermsDetails')->name('updateTermsDetails');
            Route::post('updaterzpDetails', 'Common\BaseSettingsController@updateRazorpayDetails')->name('updaterzpDetails');
            Route::post('updatezohoDetails', 'Common\BaseSettingsController@updateZohoDetails')->name('updatezohoDetails');
            Route::post('updatepipedriveDetails', 'Common\BaseSettingsController@updatepipedriveDetails')->name('updatepipedriveDetails');
            Route::post('mailchimp-prod-status', 'Common\BaseSettingsController@updateMailchimpProductStatus')->name('mailchimp-prod-status');
            Route::post('mailchimp-paid-status', 'Common\BaseSettingsController@updateMailchimpIsPaidStatus')->name('mailchimp-paid-status');
            Route::post('updatedomainCheckDetails', 'Common\BaseSettingsController@updatedomainCheckDetails')->name('updatedomainCheckDetails');
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
            Route::get('get-clients', ['as' => 'get-clients', 'uses' => 'User\ClientController@getClients']);
            Route::delete('clients-delete', 'User\ClientController@destroy');
            Route::get('get-users', 'User\ClientController@getUsers');
            Route::get('search-email', 'User\ClientController@search')->name('search-email');

            Route::resource('products', 'Product\ProductController');
            Route::get('get-products', ['as' => 'get-products', 'uses' => 'Product\ProductController@getProducts']);
            // Route::get('get-products', 'Product\ProductController@GetProducts');
            Route::delete('products-delete', 'Product\ProductController@destroy')->name('products-delete');
            Route::delete('uploads-delete', 'Product\ProductController@fileDestroy')->name('uploads-delete');

            Route::post('get-price', 'Product\ProductController@getPrice');
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
            Route::delete('plans-delete', 'Product\PlanController@destroy')->name('plans-delete');
            Route::get('get-period', 'Product\PlanController@checkSubscription')->name('get-period');
            Route::post('postInsertPeriod', 'Product\PlanController@postInsertPeriod');

            /*
             * Currency
             */

            Route::resource('currency', 'Payment\CurrencyController');
            Route::get('get-currency/datatable', ['as' => 'get-currency.datatable', 'uses' => 'Payment\CurrencyController@getCurrency']);
            Route::post('change/currency/status', ['as' => 'change.currency.status', 'uses' => 'Payment\CurrencyController@updatecurrency']);

            /*
             * Tax
             */

            Route::resource('tax', 'Payment\TaxController');
            Route::get('get-state/{state}', 'Payment\TaxController@getState');
            Route::get('get-tax', ['as' => 'get-tax', 'uses' => 'Payment\TaxController@getTax']);

            Route::get('get-taxtable', ['as' => 'get-taxtable', 'uses' => 'Payment\TaxController@getTaxTable']);
            Route::get('get-loginstate/{state}', 'Auth\AuthController@getState');

            // Route::get('get-tax', 'Payment\TaxController@GetTax');

            Route::delete('tax-delete', 'Payment\TaxController@destroy')->name('tax-delete');
            Route::patch('taxes/option', 'Payment\TaxController@options')->name('taxes/option');
            Route::post('taxes/option', 'Payment\TaxController@options');

            /*
             * Promotion
             */

            Route::resource('promotions', 'Payment\PromotionController');

            Route::get('get-promotion-code', 'Payment\PromotionController@getCode')->name('get-code');
            Route::get('get-promotions', 'Payment\PromotionController@getPromotion')->name('get-promotions');
            Route::delete('promotions-delete', 'Payment\PromotionController@destroy')->name('promotions-delete');

            /*
             * Category
             */

            Route::resource('category', 'Product\CategoryController');
            Route::get('get-category', 'Product\CategoryController@getCategory')->name('get-category');
            Route::delete('category-delete', 'Product\CategoryController@destroy')->name('category-delete');

            /*
             * Comment
             */
            Route::resource('comment', 'User\CommentController');
            Route::delete('comment-delete', 'User\CommentController@destroy')->name('comment-delete');

            /*
         * License
         */
            Route::resource('license-type', 'License\LicenseSettingsController');
            Route::get('get-license-type', 'License\LicenseSettingsController@getLicenseTypes')->name('get-license-type');
            Route::delete('license-type-delete', 'License\LicenseSettingsController@destroy')->name('license-type-delete');
            Route::get('license-permissions', 'License\LicensePermissionsController@index');
            Route::get('get-license-permission', 'License\LicensePermissionsController@getPermissions')->name('get-license-permission');
            Route::delete('add-permission', 'License\LicensePermissionsController@addPermission')->name('add-permission');
            Route::get('tick-permission', 'License\LicensePermissionsController@tickPermission')->name('tick-permission');
            /*
             * Order
             */

            Route::resource('orders', 'Order\OrderController');
            Route::get('get-orders', 'Order\OrderController@getOrders')->name('get-orders');
            Route::delete('orders-delete', 'Order\OrderController@destroy')->name('orders-delete');
            Route::patch('change-domain', 'Order\ExtendedOrderController@changeDomain');
            Route::patch('reissue-license', 'Order\ExtendedOrderController@reissueLicense');
            Route::post('edit-update-expiry', 'Order\BaseOrderController@editUpdateExpiry');
            Route::post('edit-license-expiry', 'Order\BaseOrderController@editLicenseExpiry');
            Route::post('edit-support-expiry', 'Order\BaseOrderController@editSupportExpiry');
            Route::post('edit-installation-limit', 'Order\BaseOrderController@editInstallationLimit');
            /*
             * Groups
             */

            Route::resource('groups', 'Product\GroupController');
            Route::get('get-groups', 'Product\GroupController@getGroups')->name('get-groups');
            Route::delete('groups-delete', 'Product\GroupController@destroy')->name('groups-delete');

            /*
             * Templates
             */

            Route::resource('templates', 'Common\TemplateController');
            Route::get('get-templates', ['as' => 'get-templates', 'uses' => 'Common\TemplateController@getTemplates']);
            // Route::get('get-templates', 'Common\TemplateController@GetTemplates');
            Route::delete('templates-delete', 'Common\TemplateController@destroy')->name('templates-delete');

            /*
             * Chat Script
             */
            Route::resource('chat', 'Common\ChatScriptController');
            Route::get('get-script', ['as' => 'get-script', 'uses' => 'Common\ChatScriptController@getScript']);
            Route::delete('script-delete', 'Common\ChatScriptController@destroy')->name('script-delete');
            Route::post('order/execute', 'Order\OrderController@orderExecute');
            /*
             * Invoices
             */

            Route::get('invoices', 'Order\InvoiceController@index');
            Route::get('invoices/{id}', 'Order\InvoiceController@show');
            Route::get('get-client-invoice/{id}', 'User\ClientController@getClientInvoice');
            Route::get('invoices/edit/{id}', 'Order\InvoiceController@edit');
            Route::post('invoice/edit/{id}', 'Order\InvoiceController@postEdit');
            Route::get('get-invoices', ['as' => 'get-invoices', 'uses' => 'Order\InvoiceController@getInvoices']);
            Route::get('pdf', 'Order\InvoiceController@pdf');
            Route::delete('invoice-delete', 'Order\InvoiceController@destroy')->name('invoice-delete');
            Route::get('invoice/generate', 'Order\InvoiceController@generateById');
            Route::post('generate/invoice/{user_id?}', 'Order\InvoiceController@invoiceGenerateByForm');
            Route::post('change-invoiceTotal', ['as' => 'change-invoiceTotal',
                'uses'                              => 'Order\InvoiceController@invoiceTotalChange', ]);
            Route::post('change-paymentTotal', ['as' => 'change-paymentTotal',
                'uses'                              => 'Order\InvoiceController@paymentTotalChange', ]);

            /*
             * Payment
             */
            Route::get('newPayment/receive', 'Order\InvoiceController@newPayment');
            Route::post('newPayment/receive/{clientid}', 'Order\InvoiceController@postNewPayment');
            Route::get('payment/receive', 'Order\InvoiceController@payment');
            Route::post('payment/receive/{id}', 'Order\InvoiceController@postPayment');
            Route::delete('payment-delete', 'Order\InvoiceController@deletePayment')->name('payment-delete');
            Route::get('payments/{payment_id}/edit', 'Order\InvoiceController@paymentEditById');
            Route::post('newMultiplePayment/receive/{clientid}', 'Order\InvoiceController@postNewMultiplePayment');
            Route::post('newMultiplePayment/update/{clientid}', 'Order\InvoiceController@updateNewMultiplePayment');

            /*
             * Pages
             */
            Route::resource('pages', 'Front\PageController')->middleware('admin');
            Route::get('pages/{slug}', 'Front\PageController@show');
            Route::get('page/search', 'Front\PageController@search');
            Route::get('get-pages', ['as' => 'get-pages', 'uses' => 'Front\PageController@getPages']);
            Route::delete('pages-delete', 'Front\PageController@destroy')->name('pages-delete');

            /*
             * Widgets
             */
            Route::resource('widgets', 'Front\WidgetController');
            Route::get('get-widgets', ['as' => 'get-widgets', 'uses' => 'Front\WidgetController@getPages']);
            // Route::get('get-widgets', 'Front\WidgetController@GetPages');
            Route::delete('widgets-delete', 'Front\WidgetController@destroy');

            /*
             * github
             */
            Route::get('github-auth-app', 'Github\GithubController@authForSpecificApp');
            Route::get('github-releases', 'Github\GithubController@listRepositories');
            Route::get('github-downloads', 'Github\GithubController@getDownloadCount');
            Route::get('github', 'Github\GithubController@getSettings');
            Route::post('github-setting', 'Github\GithubController@postSettings');

            /*
             * download
             */
            Route::get('download/{uploadid}/{userid}/{invoice_number}/{versionid}', 'Product\ProductController@userDownload');
            Route::get('product/download/{id}/{invoice?}', 'Product\ProductController@adminDownload');

            /*
             * check version
             */

            Route::get('version', 'HomeController@getVersion');
            Route::post('verification', 'HomeController@faveoVerification');
            Route::get('create-keys', 'HomeController@createEncryptionKeys');
            Route::get('encryption', 'HomeController@getEncryptedData');

            /*
             * plugins
             */
            Route::get('plugin', 'Common\SettingsController@plugins');

            Route::get('get-plugin', ['as' => 'get-plugin', 'uses' => 'Common\SettingsController@getPlugin']);
            // Route::get('getplugin', 'Common\SettingsController@getPlugin');
            Route::post('post-plugin', ['as' => 'post.plugin', 'uses' => 'Common\SettingsController@postPlugins']);
            Route::post('plugin/delete/{slug}', ['as' => 'delete.plugin', 'uses' => 'Common\SettingsController@deletePlugin']);
            Route::post('plugin/status/{slug}', ['as' => 'status.plugin', 'uses' => 'Common\SettingsController@statusPlugin']);

            /*
             * Cron Jobs
             */

            Route::get('job-scheduler', ['as'=>'get.job.scheduler', 'uses'=>'Common\SettingsController@getScheduler']);
            Route::patch('post-scheduler', ['as' => 'post.job.scheduler', 'uses' => 'Common\SettingsController@postSchedular'])->name('post-scheduler'); //to update job scheduler
            Route::patch('cron-days', ['as'=>'cron-days', 'uses'=>'Common\SettingsController@saveCronDays'])->name('cron-days');
            Route::get('verify-php-path', ['as' => 'verify-cron', 'uses' => 'Common\SettingsController@checkPHPExecutablePath']);
            Route::get('file-storage', 'Common\SettingsController@showFileStorage');
            Route::post('file-storage-path', 'Common\SettingsController@updateStoragePath');
            Route::get('expired-subscriptions', 'Common\CronController@eachSubscription');

            /*
             * Renew
             */

            Route::get('renew/{id}', 'Order\RenewController@renewForm');
            Route::post('renew/{id}', 'Order\RenewController@renew');
            Route::get('get-renew-cost', 'Order\RenewController@getCost');
            Route::post('client/renew/{id}', 'Order\RenewController@renewByClient');

            Route::get('generate-keys', 'HomeController@createEncryptionKeys');

            Route::get('get-code', 'WelcomeController@getCode');
            Route::get('get-currency', 'WelcomeController@getCurrency')->middleware('admin');
            Route::post('dashboard-currency/{id}', 'Payment\CurrencyController@setDashboardCurrency');
            Route::get('get-country', 'WelcomeController@getCountry')->middleware('admin');
            Route::get('country-count', 'WelcomeController@countryCount')->name('country-count')->middleware('admin');

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
            Route::post('otp/sendByAjax', 'Auth\AuthController@requestOtpFromAjax');
            Route::post('otp/verify', 'Auth\AuthController@postOtp');
            Route::get('email/verify', 'Auth\AuthController@verifyEmail');
            Route::get('resend_otp', 'Auth\AuthController@retryOTP');
            Route::get('verify', function () {
                $user = \Session::get('user');
                if ($user) {
                    return view('themes.default1.user.verify', compact('user'));
                }

                return redirect('auth/login');
            });
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
        Route::post('update/lic-code', 'HomeController@updateLicenseCode');

        Route::get('404', ['as' => 'error404', function () {
            return view('errors.404');
        }]);
    // });
