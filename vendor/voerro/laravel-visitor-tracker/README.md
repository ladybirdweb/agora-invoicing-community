# Laravel Visitor Tracker and Statistics

[![Packagist](https://img.shields.io/packagist/v/voerro/laravel-visitor-tracker.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-visitor-tracker) [![Packagist](https://img.shields.io/packagist/dm/voerro/laravel-visitor-tracker.svg?style=flat-square)](https://packagist.org/packages/voerro/laravel-visitor-tracker) [![Build Status](https://travis-ci.org/voerro/laravel-visitor-tracker.svg?branch=master)](https://travis-ci.org/voerro/laravel-visitor-tracker) [![StyleCI](https://styleci.io/repos/116011849/shield?branch=master)](https://styleci.io/repos/116011849) [![Packagist](https://img.shields.io/packagist/l/voerro/laravel-visitor-tracker.svg?style=flat-square)](https://opensource.org/licenses/MIT)

Track your authenticated and unauthenticated visitors, login attempts, ajax requests, and more. Includes a controller and a bunch of routes and views to display the statistics, as well as a helper class to fetch the statistics easily (in case you want to display the statistics yourself).

[Live Demo](http://statistics.voerro.com)

## Installation - Basic
1) Install the package using composer:

```bash
composer require voerro/laravel-visitor-tracker
```

2) Run the migration to install the package's table to record visits to by executing:

```bash
php artisan migrate
```

3) Add the middleware to `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    ...
    'web' => [
        ...
        \Voerro\Laravel\VisitorTracker\Middleware\RecordVisits::class,
    ],
    ...
];
```

4) Laravel 5.5 has package auto-discovery. If you have an older version, register the service provider in `config/app.php`:

```php
...
'providers' => [
    ...
    Voerro\Laravel\VisitorTracker\VisitorTrackerServiceProvider::class,
    ...
],
...
```

If you want to fetch and display the visitor statistics yourself register the facade in the same file:

```php
...
'aliases' => [
    ...
    'VisitStats' => Voerro\Laravel\VisitorTracker\Facades\VisitStats::class,
    ...
],
...
```

5) Publish the config file, assets, and views by running:

```bash
php artisan vendor:publish
```

Choose `Voerro\Laravel\VisitorTracker\VisitorTrackerServiceProvider` in the provided list.

## Installation - Geoapi

The tracker uses external API to fetch the geolocation data. To turn geoapi off set the `geoip_on` setting in the config file to false. To change a provider change the `geoip_driver` field. The supported drivers are listed in the configuration file. You might need to fill out additional API keys depending on the driver you choose.

Since fetching data from an external API takes time, the operation is queued an performed asynchronously. This is done using Laravel Jobs and probably won't work on a shared hosting. There are multiple drivers supported. We'll describe how to set up the database driver.

First, in your `.env` file you need to set:

```php
QUEUE_DRIVER=database
```
Then run these commands one after another:

```bash
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

Finally, you need to start the worker that will take care of the queue. Run the following command and keep it running:

```bash
php artisan queue:work
```

Read more on Queues and Jobs in the [Laravel documentation](https://laravel.com/docs/5.5/queues). [This section](https://laravel.com/docs/5.5/queues#supervisor-configuration) describes how to restart the queue worker automatically in case the process fails.

P.S. You need to restart the worker every time you've made changes to the package's config file.

## Configuration

Check out the `config/visitortracker.php` file. It is well commented and additional explanations are not required. You can exclude certain user groups, individual users, and certain requests from being tracked there among other things.

## Testing

The external API calls to retrieve geolocation information are disabled in the testing environment. Otherwise your tests would run really slow, since the tracker tracks all the requests.

## Displaying Statistics

The package comes with a controller and a bunch of routes and views to display statistics. You can fetch and display the stats yourself using the `VisitStats` class, but we'll talk about it later. The provided views are uncomplicated and styled with the standard Bootstrap classes.

To install the in-built routes add this line to your `routes.php` file:

```php
VisitStats::routes();
```

You can put this line inside a group to restrict the access with middlewares and/or to add a prefix to the routes. For example, like this:

```php
Route::middleware('auth')->prefix('admin')->group(function () {
    VisitStats::routes();
});
```

You can integrate the views into your existing layouts. Take a look at the `Views` section of the config file. All the routes are named so you could easily add and style links to all the pages. Below is the complete list of routes.

| Route name | Description |
| --- | --- |
| visitortracker.summary | A small summary page |
| visitortracker.all_requests | A list of all the requests |
| visitortracker.visits | A list of page visits (same as all request minus ajax calls, bot visits, and login attempts) |
| visitortracker.ajax_requests | A list of AJAX requests |
| visitortracker.bots | A list of visits from bots/crawlers |
| visitortracker.login_attempts | A list of login attempts (check the "Tracking login attempts" section of the config file) |
| visitortracker.countries | A list of countries with the number of visits and unique visitors in each ordered by the date of last visit |
| visitortracker.os | A list of operating systems with the number of visits and unique visitors from each ordered by the date of last visit |
| visitortracker.browsers | A list of browsers with the number of visits and unique visitors from each ordered by the date of last visit |
| visitortracker.languages | A list of browser languages with the number of visits and unique visitors in each ordered by the date of last visit |
| visitortracker.unique | A list of unique visitors (unique IPs) ordered by the date of last visit |
| visitortracker.users | A list of registered users with the total number of visits ordered by the date of last visit |
| visitortracker.urls | A list of URLs with the total number of visits and unique visitors ordered by the date of last visit |

## What Information is Being Collected

This is the data that is being collected by the tracker.

| Database field | Description |
| --- | --- |
| user_id | An id of an authenticated user performing the request |
| ip | e.g. '127.0.0.1' |
| method | e.g. 'GET' |
| is_ajax | Whether the request is an AJAX request |
| url | e.g. 'http://voerro.com' |
| referer | e.g. 'http://google.com' |
| user_agent | e.g. 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:58.0) Gecko/20100101 Firefox/58.0' |
| is_desktop | Whether the request is made from a desktop |
| is_mobile | Whether the request is made from a mobile device |
| is_bot | Whether the visitor is a bot/crawler |
| bot | e.g. 'Googlebot' |
| os_family | e.g. 'linux' |
| os | e.g. 'Ubuntu' |
| browser_family | e.g. 'firefox' |
| browser | e.g. 'Firefox 58.0' |
| is_login_attempt | Whether the request is a login attempt |
| country | e.g. 'Russia' |
| country_code | e.g. 'RU' |
| city | e.g. 'Moscow' |
| lat | Latitude |
| long | Longitude |
| browser_language_family | e.g. 'en' |
| browser_language | e.g. 'en-US' |
| created_at | A standard Laravel field which is also used as a visit/request datetime |

The package uses `piwik/device-detector` to parse the user agent.

## Manually Fetching and Displaying Statistics

In case you are not content with the provided views, you can use the `Voerro\Laravel\VisitorTracker\Facades\VisitStats` class to fetch the statistics data and then make your own controller and views to display this data.

Take a look at the controller located at `src/Controllers/StatisticsController.php` to understand how to work with the class, it's pretty simple. The original class is located at `src/VisitStats.php` and all the methods inside are documented, in case you need more insights.

## License

This is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).