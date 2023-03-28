<?php

use App\Http\Controllers\Api;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Common;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FreeTrailController;
use App\Http\Controllers\Front;
use App\Http\Controllers\Github;
use App\Http\Controllers\Google2FAController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Installer;
use App\Http\Controllers\Jobs;
use App\Http\Controllers\License;
use App\Http\Controllers\License\LocalizedLicenseController;
use App\Http\Controllers\Order;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Product;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\Tenancy;
use App\Http\Controllers\ThirdPartyAppController;
use App\Http\Controllers\User;
use App\Http\Controllers\WelcomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token(), ],
        200);
});

/*
* Installer Routes
*/

Route::prefix('install')->name('AgoraInstaller::')->middleware('isInstalled')->group(function () {
    Route::get('/', [Installer\WelcomeController::class, 'welcome'])->name('welcome');

    Route::get('requirements', [Installer\RequirementsController::class, 'requirements'])->name('requirements');

    Route::get('permissions', [Installer\PermissionsController::class, 'permissions'])->name('permissions');

    Route::get('environment', [Installer\EnvironmentController::class, 'environmentMenu'])->name('environment');

    Route::get('environment/wizard', [Installer\EnvironmentController::class, 'environmentWizard'])->name('environmentWizard');

    Route::post('environment/saveWizard', [Installer\EnvironmentController::class, 'saveWizard'])->name('environmentSaveWizard');

    Route::get('database', [Installer\DatabaseController::class, 'database'])->name('database');

    Route::get('final', [Installer\FinalController::class, 'finish'])->name('final');
});

Route::middleware('installAgora')->group(function () {
    Route::get('pricing', [Front\CartController::class, 'cart'])->name('pricing');
    Route::get('group/{templateid}/{groupid}/', [Front\PageController::class, 'pageTemplates']);
    Route::post('cart/remove', [Front\CartController::class, 'cartRemove']);
    Route::post('update-agent-qty', [Front\CartController::class, 'updateAgentQty']);
    Route::post('update-qty', [Front\CartController::class, 'updateProductQty']);
    Route::post('reduce-product-qty', [Front\CartController::class, 'reduceProductQty']);
    Route::post('reduce-agent-qty', [Front\CartController::class, 'reduceAgentQty']);
    Route::post('cart/clear', [Front\CartController::class, 'clearCart']);
    Route::get('show/cart', [Front\CartController::class, 'showCart']);

    Route::get('checkout', [Front\CheckoutController::class, 'checkoutForm']);
    Route::match(['post', 'patch'], 'checkout-and-pay', [Front\CheckoutController::class, 'postCheckout']);

    Route::post('pricing/update', [Front\CartController::class, 'addCouponUpdate']);
    Route::post('mail-chimp/subcribe', [Common\MailChimpController::class, 'addSubscriberByClientPanel']);
    Route::get('mailchimp', [Common\MailChimpController::class, 'mailChimpSettings'])->middleware('admin');
    Route::patch('mailchimp', [Common\MailChimpController::class, 'postMailChimpSettings']);
    Route::get('mail-chimp/mapping', [Common\MailChimpController::class, 'mapField'])->middleware('admin');
    Route::patch('mail-chimp/mapping', [Common\MailChimpController::class, 'postMapField']);
    Route::patch('mailchimp-ispaid/mapping', [Common\MailChimpController::class, 'postIsPaidMapField']);
    Route::patch('mailchimp-group/mapping', [Common\MailChimpController::class, 'postGroupMapField']);
    Route::get('get-group-field/{value}', [Common\MailChimpController::class, 'addInterestFieldsToAgora']);
    Route::get('contact-us', [Front\PageController::class, 'contactUs']);
    Route::post('contact-us', [Front\PageController::class, 'postContactUs']);

    /*
     * Front Client Pages
     */

    Route::post('first-login', [FreeTrailController::class, 'firstLoginAttempt']);

    Route::get('my-invoices', [Front\ClientController::class, 'invoices'])->name('my-invoices');

    Route::get('get-my-invoices', [Front\ClientController::class, 'getInvoices'])->name('get-my-invoices');
    Route::get('get-my-invoices/{orderid}/{userid}', [Front\ClientController::class, 'getInvoicesByOrderId']);

    Route::get('get-my-payment/{orderid}/{userid}', [Front\ClientController::class, 'getPaymentByOrderId'])->name('get-my-payment');

    Route::get('get-my-payment-client/{orderid}/{userid}', [Front\ClientController::class, 'getPaymentByOrderIdClient'])->name('get-my-payment-client');

    Route::get('my-orders', [Front\ClientController::class, 'orders']);
    Route::get('get-my-orders', [Front\ClientController::class, 'getOrders'])->name('get-my-orders');
    Route::get('my-subscriptions', [Front\ClientController::class, 'subscriptions']);
    Route::get('get-my-subscriptions', [Front\ClientController::class, 'getSubscriptions']);
    Route::get('my-invoice/{id}', [Front\ClientController::class, 'getInvoice']);
    Route::get('my-order/{id}', [Front\ClientController::class, 'getOrder']);
    Route::get('uploadFile', [License\LocalizedLicenseController::class, 'storeFile']);
    Route::get('my-profile', [Front\ClientController::class, 'profile']);
    Route::patch('my-profile', [Front\ClientController::class, 'postProfile']);
    Route::patch('my-password', [Front\ClientController::class, 'postPassword']);
    Route::get('paynow/{id}', [Front\CheckoutController::class, 'payNow']);

    Route::get('get-versions/{productid}/{clientid}/{invoiceid}/', [Front\ClientController::class, 'getVersionList'])->name('get-versions');
    Route::get('get-github-versions/{productid}/{clientid}/{invoiceid}/', [Front\ClientController::class, 'getGithubVersionList'])->name('get-github-versions');

    // Post Route For Make Razorpay Payment Request
    Route::post('payment/{invoice}', [RazorpayController::class, 'payment'])->name('payment');

    Route::get('downloadLicenseFile', [License\LocalizedLicenseController::class, 'downloadFile'])->name('event.rsvp')->middleware('signed');
    Route::get('downloadPrivate/{orderNo}', [License\LocalizedLicenseController::class, 'downloadPrivate']);
    Route::get('LocalizedLicense/downloadLicense/{fileName}', [License\LocalizedLicenseController::class, 'downloadFileAdmin']);
    Route::get('request', [License\LocalizedLicenseController::class, 'tempOrderLink']);
    Route::get('LocalizedLicense/downloadPrivateKey/{fileName}', [License\LocalizedLicenseController::class, 'downloadPrivateKeyAdmin']);

    /*
     * 2FA Routes
     */

    Route::post('/2fa/enable', [Google2FAController::class, 'enableTwoFactor']);
    Route::post('2fa/disable/{userId?}', [Google2FAController::class, 'disableTwoFactor']);
    Route::get('/2fa/validate', [Google2FAController::class, 'getValidateToken']);
    Route::get('verify-2fa', [Google2FAController::class, 'verify2fa']);
    Route::get('2fa/loginValidate', [Google2FAController::class, 'postLoginValidateToken'])->name('2fa/loginValidate');
    Route::post('2fa/setupValidate', [Google2FAController::class, 'postSetupValidateToken']);
    Route::get('verify-password', [Google2FAController::class, 'verifyPassword']);
    Route::post('2fa-recovery-code', [Google2FAController::class, 'generateRecoveryCode']);
    Route::get('get-recovery-code', [Google2FAController::class, 'getRecoveryCode']);
    Route::get('recovery-code', [Google2FAController::class, 'showRecoveryCode']);
    Route::post('verify-recovery-code', [Google2FAController::class, 'verifyRecoveryCode'])->name('verify-recovery-code');
    /*
     * Social Media
     */

    Route::resource('social-media', Common\SocialMediaController::class);
    Route::get('get-social-media', [Common\SocialMediaController::class, 'getSocials'])->name('get-social-media');
    /*
     * Tweeter api
     */
    Route::get('twitter', [Common\SocialMediaController::class, 'getTweets'])->name('twitter');

    Route::auth();
    Route::post('auth/register', [Auth\RegisterController::class, 'postRegister'])->name('auth/register');
    Route::get('auth/logout', [Auth\LoginController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index']);

    Route::get('activate/{token}', [Auth\AuthController::class, 'activate']);

    /*
     * Client
     */

    /*
     * Client
     */

    /*
     * Client
     */

    /*
     * Client
     */

    /*
     * Profile Process
     */

    Route::get('profile', [User\ProfileController::class, 'profile']);
    Route::patch('profile', [User\ProfileController::class, 'updateProfile']);
    Route::patch('password', [User\ProfileController::class, 'updatePassword']);

    /*
     * Settings
     */

    Route::post('changeLogo', [Common\SettingsController::class, 'delete']);

    Route::get('settings', [Common\SettingsController::class, 'settings']);
    Route::get('settings/system', [Common\SettingsController::class, 'settingsSystem']);
    Route::patch('settings/system', [Common\SettingsController::class, 'postSettingsSystem']);
    Route::get('settings/email', [Common\EmailSettingsController::class, 'settingsEmail'])->middleware('auth');
    Route::patch('settings/email', [Common\EmailSettingsController::class, 'postSettingsEmail']);
    Route::get('settings/template', [Common\SettingsController::class, 'settingsTemplate']);
    Route::patch('settings/template', [Common\SettingsController::class, 'postSettingsTemplate']);
    Route::patch('settings/error', [Common\SettingsController::class, 'postSettingsError']);
    Route::get('settings/activitylog', [Common\SettingsController::class, 'settingsActivity']);
    Route::get('settings/maillog', [Common\SettingsController::class, 'settingsMail']);
    Route::get('get-activity', [Common\SettingsController::class, 'getActivity'])->name('get-activity');
    Route::get('get-email', [Common\SettingsController::class, 'getMails'])->name('get-email');
    Route::delete('activity-delete', [Common\SettingsController::class, 'destroy'])->name('activity-delete');
    Route::delete('email-delete', [Common\SettingsController::class, 'destroyEmail'])->name('email-delete');
    Route::post('licenseDetails', [Common\BaseSettingsController::class, 'licenseDetails'])->name('licenseDetails');
    Route::post('updateDetails', [Common\BaseSettingsController::class, 'updateDetails'])->name('updateDetails');
    Route::post('captchaDetails', [Common\BaseSettingsController::class, 'captchaDetails'])->name('captchaDetails');
    Route::post('updatemobileDetails', [Common\BaseSettingsController::class, 'updateMobileDetails'])->name('updatemobileDetails');
    Route::post('updateemailDetails', [Common\BaseSettingsController::class, 'updateEmailDetails'])->name('updateemailDetails');
    Route::post('updatetwitterDetails', [Common\BaseSettingsController::class, 'updateTwitterDetails'])->name('updatetwitterDetails');
    Route::post('updateMailchimpDetails', [Common\BaseSettingsController::class, 'updateMailchimpDetails'])->name('updateMailchimpDetails');
    Route::post('updateTermsDetails', [Common\BaseSettingsController::class, 'updateTermsDetails'])->name('updateTermsDetails');
    Route::post('updatezohoDetails', [Common\BaseSettingsController::class, 'updateZohoDetails'])->name('updatezohoDetails');
    Route::post('updatepipedriveDetails', [Common\BaseSettingsController::class, 'updatepipedriveDetails'])->name('updatepipedriveDetails');
    Route::post('mailchimp-prod-status', [Common\BaseSettingsController::class, 'updateMailchimpProductStatus'])->name('mailchimp-prod-status');
    Route::post('mailchimp-paid-status', [Common\BaseSettingsController::class, 'updateMailchimpIsPaidStatus'])->name('mailchimp-paid-status');
    Route::post('updatedomainCheckDetails', [Common\BaseSettingsController::class, 'updatedomainCheckDetails'])->name('updatedomainCheckDetails');
    Route::get('system-managers', [Common\SystemManagerController::class, 'getSystemManagers'])->name('system-managers');
    Route::get('search-admins', [Common\SystemManagerController::class, 'searchAdmin'])->name('search-admins');
    Route::post('replace-acc-manager', [Common\SystemManagerController::class, 'replaceAccountManager'])->name('replace-acc-manager');
    Route::post('replace-sales-manager', [Common\SystemManagerController::class, 'replaceSalesManager'])->name('replace-sales-manager');

    /*
     * Client
     */

    Route::resource('clients', User\ClientController::class);
    Route::get('deleted-users', [User\SoftDeleteController::class, 'index']);
    Route::get('soft-delete', [User\SoftDeleteController::class, 'softDeletedUsers'])->name('soft-delete');
    Route::get('clients/{id}/restore', [User\SoftDeleteController::class, 'restoreUser']);
    Route::delete('permanent-delete-client', [User\SoftDeleteController::class, 'permanentDeleteUser']);
    Route::get('getClientDetail/{id}', [User\ClientController::class, 'getClientDetail']);
    Route::get('getPaymentDetail/{id}', [User\ClientController::class, 'getPaymentDetail']);
    Route::get('getOrderDetail/{id}', [User\ClientController::class, 'getOrderDetail']);
    Route::get('get-clients', [User\ClientController::class, 'getClients'])->name('get-clients');
    Route::delete('clients-delete', [User\ClientController::class, 'destroy']);
    Route::get('get-users', [User\ClientController::class, 'getUsers']);
    Route::get('search-email', [User\ClientController::class, 'search'])->name('search-email');

    Route::resource('products', Product\ProductController::class);
    Route::get('get-products', [Product\ProductController::class, 'getProducts'])->name('get-products');
    // Route::get('get-products', [Product\ProductController::class, 'GetProducts']);
    Route::delete('products-delete', [Product\ProductController::class, 'destroy'])->name('products-delete');
    Route::delete('uploads-delete', [Product\ProductController::class, 'fileDestroy'])->name('uploads-delete');

    Route::post('get-price', [Product\ProductController::class, 'getPrice']);
    Route::get('get-subscription/{id}', [Product\ProductController::class, 'getSubscriptionCheck']);
    Route::get('edit-upload/{id}', [Product\ProductController::class, 'editProductUpload']);
    Route::get('get-upload/{id}', [Product\ProductController::class, 'getUpload'])->name('get-upload');
    Route::post('upload/save', [Product\ProductController::class, 'save'])->name('upload/save');
    Route::post('chunkupload', [Product\ProductController::class, 'uploadFile']);
    Route::patch('upload/{id}', [Product\ProductController::class, 'uploadUpdate']);
    Route::get('get-group-url', [Product\GroupController::class, 'generateGroupUrl']);

    /*
     * Plan
     */

    Route::resource('plans', Product\PlanController::class);
    Route::get('get-plans', [Product\PlanController::class, 'getPlans'])->name('get-plans');
    // Route::get('get-plans', [Product\PlanController::class, 'GetPlans']);
    Route::delete('plans-delete', [Product\PlanController::class, 'destroy'])->name('plans-delete');
    Route::get('get-period', [Product\PlanController::class, 'checkSubscription'])->name('get-period');
    Route::post('postInsertPeriod', [Product\PlanController::class, 'postInsertPeriod']);

    /*
     * Currency
     */

    Route::resource('currency', Payment\CurrencyController::class);
    Route::get('get-currency/datatable', [Payment\CurrencyController::class, 'getCurrency'])->name('get-currency.datatable');
    Route::post('change/currency/status', [Payment\CurrencyController::class, 'updatecurrency'])->name('change.currency.status');

    /*
     * Tax
     */

    Route::resource('tax', Payment\TaxController::class);
    Route::get('get-state/{state}', [Payment\TaxController::class, 'getState']);
    Route::get('get-tax', [Payment\TaxController::class, 'getTax'])->name('get-tax');

    Route::get('get-taxtable', [Payment\TaxController::class, 'getTaxTable'])->name('get-taxtable');
    Route::get('get-loginstate/{state}', [Auth\AuthController::class, 'getState']);

    // Route::get('get-tax', [Payment\TaxController::class, 'GetTax']);

    Route::delete('tax-delete', [Payment\TaxController::class, 'destroy'])->name('tax-delete');
    Route::post('taxes/option', [Payment\TaxController::class, 'saveTaxOptionSetting'])->name('taxes/option');
    Route::post('taxes/class', [Payment\TaxController::class, 'saveTaxClassSetting']);

    /*
     * Promotion
     */

    Route::resource('promotions', Payment\PromotionController::class);

    Route::get('get-promotion-code', [Payment\PromotionController::class, 'getCode'])->name('get-code');
    Route::get('get-promotions', [Payment\PromotionController::class, 'getPromotion'])->name('get-promotions');
    Route::delete('promotions-delete', [Payment\PromotionController::class, 'destroy'])->name('promotions-delete');

    /*
     * Category
     */

    Route::resource('category', Product\CategoryController::class);
    Route::get('get-category', [Product\CategoryController::class, 'getCategory'])->name('get-category');
    Route::delete('category-delete', [Product\CategoryController::class, 'destroy'])->name('category-delete');

    /*
     * Comment
     */
    Route::resource('comment', User\CommentController::class);
    Route::delete('comment-delete', [User\CommentController::class, 'destroy'])->name('comment-delete');

    /*
         * License
         */
    Route::resource('license-type', License\LicenseSettingsController::class);
    Route::get('get-license-type', [License\LicenseSettingsController::class, 'getLicenseTypes'])->name('get-license-type');
    Route::delete('license-type-delete', [License\LicenseSettingsController::class, 'destroy'])->name('license-type-delete');
    Route::get('license-permissions', [License\LicensePermissionsController::class, 'index']);
    Route::get('get-license-permission', [License\LicensePermissionsController::class, 'getPermissions'])->name('get-license-permission');
    Route::delete('add-permission', [License\LicensePermissionsController::class, 'addPermission'])->name('add-permission');
    Route::get('tick-permission', [License\LicensePermissionsController::class, 'tickPermission'])->name('tick-permission');
    /*
     * Order
     */

    Route::resource('orders', Order\OrderController::class);
    Route::get('get-orders', [Order\OrderController::class, 'getOrders'])->name('get-orders');
    Route::get('get-product-versions/{product}', [Order\OrderSearchController::class, 'getProductVersions'])->name('get-product-versions');
    Route::delete('orders-delete', [Order\OrderController::class, 'destroy'])->name('orders-delete');
    Route::patch('change-domain', [Order\ExtendedOrderController::class, 'changeDomain']);
    Route::patch('reissue-license', [Order\ExtendedOrderController::class, 'reissueLicense']);
    Route::post('edit-update-expiry', [Order\BaseOrderController::class, 'editUpdateExpiry']);
    Route::post('edit-license-expiry', [Order\BaseOrderController::class, 'editLicenseExpiry']);
    Route::post('edit-support-expiry', [Order\BaseOrderController::class, 'editSupportExpiry']);
    Route::post('edit-installation-limit', [Order\BaseOrderController::class, 'editInstallationLimit']);

    Route::post('choose', [License\LocalizedLicenseController::class, 'chooseLicenseMode']);
    Route::get('LocalizedLicense', function () {
        return view('themes.default1.common.Localized');
    })->middleware('auth');
    Route::get('LocalizedLicense/delete/{fileName}', [License\LocalizedLicenseController::class, 'deleteFile']);
    //Route::post('LocalizedLicense/updateLicenseFile/{fileName}',[LocalizedLicenseController::class,'fileEdit']);

    /*
     * Groups
     */

    Route::resource('groups', Product\GroupController::class);
    Route::get('get-groups', [Product\GroupController::class, 'getGroups'])->name('get-groups');
    Route::delete('groups-delete', [Product\GroupController::class, 'destroy'])->name('groups-delete');

    /*
     * Templates
     */

    Route::resource('template', Common\TemplateController::class);
    Route::get('get-templates', [Common\TemplateController::class, 'getTemplates'])->name('get-templates');
    // Route::get('get-templates', [Common\TemplateController::class, 'GetTemplates']);
    Route::delete('templates-delete', [Common\TemplateController::class, 'destroy'])->name('templates-delete');

    /**
     * Queue.
     */
    Route::get('queue', [Jobs\QueueController::class, 'index'])->name('queue');
    Route::get('get-queue', [Jobs\QueueController::class, 'getQueues'])->name('get-queue');
    Route::get('queue/{id}', [Jobs\QueueController::class, 'edit'])->name('queue.edit');
    Route::post('queue/{id}', [Jobs\QueueController::class, 'update'])->name('queue.update');

    Route::post('queue/{queue}/activate', [Jobs\QueueController::class, 'activate']);
    Route::get('form/queue', [Jobs\QueueController::class, 'getForm'])->name('queue.form');
    // Route::get('queue-monitoring', [Jobs\QueueController::class, 'monitorQueues']]);

    /*
     * Chat Script
     */
    Route::resource('chat', Common\ChatScriptController::class);
    Route::get('get-script', [Common\ChatScriptController::class, 'getScript'])->name('get-script');
    Route::delete('script-delete', [Common\ChatScriptController::class, 'destroy'])->name('script-delete');
    Route::post('order/execute', [Order\OrderController::class, 'orderExecute']);
    /*
     * Invoices
     */

    Route::get('invoices', [Order\InvoiceController::class, 'index']);
    Route::get('invoices/{id}', [Order\InvoiceController::class, 'show']);
    Route::get('get-client-invoice/{id}', [User\ClientController::class, 'getClientInvoice']);
    Route::get('invoices/edit/{id}', [Order\InvoiceController::class, 'edit']);
    Route::post('invoice/edit/{id}', [Order\InvoiceController::class, 'postEdit']);
    Route::get('get-invoices', [Order\InvoiceController::class, 'getInvoices'])->name('get-invoices');
    Route::get('pdf', [Order\InvoiceController::class, 'pdf']);
    Route::delete('invoice-delete', [Order\InvoiceController::class, 'destroy'])->name('invoice-delete');
    Route::get('invoice/generate', [Order\InvoiceController::class, 'generateById']);
    Route::post('generate/invoice/{user_id?}', [Order\InvoiceController::class, 'invoiceGenerateByForm']);
    Route::post('change-invoiceTotal', [Order\InvoiceController::class, 'invoiceTotalChange'])->name('change-invoiceTotal');
    Route::post('change-paymentTotal', [Order\InvoiceController::class, 'paymentTotalChange'])->name('change-paymentTotal');

    /*
     * Payment
     */
    Route::get('newPayment/receive', [Order\InvoiceController::class, 'newPayment']);
    Route::post('newPayment/receive/{clientid}', [Order\InvoiceController::class, 'postNewPayment']);
    Route::get('payment/receive', [Order\InvoiceController::class, 'payment']);
    Route::post('payment/receive/{id}', [Order\InvoiceController::class, 'postPayment']);
    Route::delete('payment-delete', [Order\InvoiceController::class, 'deletePayment'])->name('payment-delete');
    Route::get('payments/{payment_id}/edit', [Order\InvoiceController::class, 'paymentEditById']);
    Route::post('newMultiplePayment/receive/{clientid}', [Order\InvoiceController::class, 'postNewMultiplePayment']);
    Route::post('newMultiplePayment/update/{clientid}', [Order\InvoiceController::class, 'updateNewMultiplePayment']);

    /*
     * Pages
     */
    Route::resource('pages', Front\PageController::class)->middleware('admin');
    Route::get('pages/{slug}', [Front\PageController::class, 'show']);
    Route::get('page/search', [Front\PageController::class, 'search']);
    Route::get('get-pages', [Front\PageController::class, 'getPages'])->name('get-pages');
    Route::delete('pages-delete', [Front\PageController::class, 'destroy'])->name('pages-delete');

    /*
     * Widgets
     */
    Route::resource('widgets', Front\WidgetController::class);
    Route::get('get-widgets', [Front\WidgetController::class, 'getPages'])->name('get-widgets');
    // Route::get('get-widgets', [Front\WidgetController::class, 'GetPages']);
    Route::delete('widgets-delete', [Front\WidgetController::class, 'destroy']);

    /*
     * github
     */
    Route::get('github-auth-app', [Github\GithubController::class, 'authForSpecificApp']);
    Route::get('github-releases', [Github\GithubController::class, 'listRepositories']);
    Route::get('github-downloads', [Github\GithubController::class, 'getDownloadCount']);
    Route::get('github', [Github\GithubController::class, 'getSettings']);
    Route::post('github-setting', [Github\GithubController::class, 'postSettings']);

    /*
     * download
     */
    Route::get('download/{uploadid}/{userid}/{invoice_number}/{versionid}', [Product\ProductController::class, 'userDownload']);
    Route::get('product/download/{id}/{invoice?}', [Product\ProductController::class, 'adminDownload']);

    /*
     * check version
     */

    Route::get('version', [HomeController::class, 'getVersion']);
    Route::post('verification', [HomeController::class, 'faveoVerification']);
    Route::get('create-keys', [HomeController::class, 'createEncryptionKeys']);
    Route::get('encryption', [HomeController::class, 'getEncryptedData']);

    /*
     * plugins
     */
    Route::get('plugin', [Common\SettingsController::class, 'plugins']);

    // Route::get('get-plugin', [Common\PaymentSettingsController::class, 'getPlugin'])->name('get-plugin');
    // Route::get('getplugin', [Common\SettingsController::class, 'getPlugin']);
    Route::post('post-plugin', [Common\PaymentSettingsController::class, 'postPlugins'])->name('post.plugin');
    Route::post('plugin/delete/{slug}', [Common\PaymentSettingsController::class, 'deletePlugin'])->name('delete.plugin');
    Route::post('plugin/status/{slug}', [Common\PaymentSettingsController::class, 'statusPlugin'])->name('status.plugin');

    /*
     * Cron Jobs
     */

    Route::get('job-scheduler', [Common\SettingsController::class, 'getScheduler'])->name('get.job.scheduler');
    Route::patch('post-scheduler', [Common\SettingsController::class, 'postSchedular'])->name('post.job.scheduler')->name('post-scheduler'); //to update job scheduler
    Route::patch('cron-days', [Common\SettingsController::class, 'saveCronDays'])->name('cron-days')->name('cron-days');
    Route::post('verify-php-path', [Common\SettingsController::class, 'checkPHPExecutablePath'])->name('verify-cron');
    Route::get('file-storage', [Common\SettingsController::class, 'showFileStorage']);
    Route::post('file-storage-path', [Common\SettingsController::class, 'updateStoragePath']);
    Route::get('expired-subscriptions', [Common\CronController::class, 'eachSubscription']);

    /*
     * Renew
     */

    Route::get('renew/{id}', [Order\RenewController::class, 'renewForm']);
    Route::post('renew/{id}', [Order\RenewController::class, 'renew']);
    Route::get('get-renew-cost', [Order\RenewController::class, 'getCost']);
    Route::post('client/renew/{id}', [Order\RenewController::class, 'renewByClient']);

    Route::get('generate-keys', [HomeController::class, 'createEncryptionKeys']);

    Route::get('get-code', [WelcomeController::class, 'getCode']);
    Route::get('get-currency', [WelcomeController::class, 'getCurrency'])->middleware('admin');
    Route::post('dashboard-currency/{id}', [Payment\CurrencyController::class, 'setDashboardCurrency']);
    Route::get('get-country', [WelcomeController::class, 'getCountry'])->middleware('admin');
    Route::get('country-count', [WelcomeController::class, 'countryCount'])->name('country-count')->middleware('admin');

    /*
    Cloud APIs
     */

    Route::resource('third-party-keys', ThirdPartyAppController::class);
    Route::get('get-third-party-app', [ThirdPartyAppController::class, 'getThirdPartyDetails'])->name('get-third-party-app');
    Route::get('get-app-key', [ThirdPartyAppController::class, 'getAppKey'])->name('get-app-key');
    Route::delete('third-party-delete', [ThirdPartyAppController::class, 'destroy'])->name('third-party-delete');
    Route::post('create/tenant', [Tenancy\TenantController::class, 'createTenant']);
    Route::post('change/domain', [Tenancy\TenantController::class, 'changeDomain']);
    Route::get('view/tenant', [Tenancy\TenantController::class, 'viewTenant'])->middleware('admin');
    Route::get('get-tenants', [Tenancy\TenantController::class, 'getTenants'])->name('get-tenants')->middleware('admin');
    Route::delete('delete-tenant', [Tenancy\TenantController::class, 'destroyTenant'])->name('delete-tenant')->middleware('admin');
    Route::get('delete/domain/{orderNumber}/{isDelete}', [Tenancy\TenantController::class, 'DeleteCloudInstanceForClient']);

    Route::delete('delete-tenant', [Tenancy\TenantController::class, 'destroyTenant'])->name('delete-tenant')->middleware('admin');

    Route::post('cloud-details', [Tenancy\TenantController::class, 'saveCloudDetails'])->name('cloud-details')->middleware('admin');

    Route::post('upgrade-plan-for-cloud', [Tenancy\CloudExtraActivities::class, 'upgradePlan']);

    /*
     * Api
     */
    Route::prefix('api')->group(function () {
        /*
         * Unautherised requests
         */
        Route::get('check-url', [Api\ApiController::class, 'checkDomain']);
    });

    /*
     * Api Keys
     */
    Route::get('apikeys', [Common\SettingsController::class, 'getKeys']);
    Route::patch('apikeys', [Common\SettingsController::class, 'postKeys']);
    Route::post('login', [Auth\LoginController::class, 'login'])->name('login');
    // Route::post('login', [Auth\LoginController::class, 'login'])->name('login');

    Route::get('otp/send', [Auth\AuthController::class, 'requestOtp']);
    Route::post('otp/sendByAjax', [Auth\AuthController::class, 'requestOtpFromAjax']);
    Route::post('otp/verify', [Auth\AuthController::class, 'postOtp']);
    Route::get('email/verify', [Auth\AuthController::class, 'verifyEmail']);
    Route::get('resend_otp', [Auth\AuthController::class, 'retryOTP']);
    Route::get('verify', function () {
        $user = \Session::get('user');
        if ($user) {
            return view('themes.default1.user.verify', compact('user'));
        }

        return redirect('login');
    });
});
/*
* Faveo APIs
*/
Route::post('serial', [HomeController::class, 'serial']);
Route::post('v2/serial', [HomeController::class, 'serialV2']);
Route::get('download/faveo', [HomeController::class, 'downloadForFaveo']);
Route::get('version/latest', [HomeController::class, 'latestVersion']);
Route::post('update-latest-version', [HomeController::class, 'updateLatestVersion']);
Route::post('v1/checkUpdatesExpiry', [HomeController::class, 'checkUpdatesExpiry']);
Route::post('update/lic-code', [HomeController::class, 'updateLicenseCode']);
Route::get('new-version-available', [HomeController::class, 'isNewVersionAvailable']);
Route::post('update-installation-detail', [HomeController::class, 'updateInstallationDetails']);
Route::get('verify/third-party-token', [Tenancy\TenantController::class, 'verifyThirdPartyToken']);

Route::get('404', function () {
    return view('errors.404');
})->name('error404');
// });
