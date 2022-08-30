# Laravel SparkPost Driver

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vemcogroup/laravel-sparkpost-driver.svg?style=flat-square)](https://packagist.org/packages/vemcogroup/laravel-sparkpost-driver)
[![Total Downloads](https://img.shields.io/packagist/dt/vemcogroup/laravel-sparkpost-driver.svg?style=flat-square)](https://packagist.org/packages/vemcogroup/laravel-sparkpost-driver)

## Description

This package allows you to still use SparkPost as MailDriver in Laravel.

This package is inspired by: https://github.com/clarification/sparkpost-laravel-driver and updated with driver from Laravel 5.8.x

## Version

Find the correct version to use in the table below:

| Laravel version | Version |
|:---------------:|:-------:|
|       6.x       |   2.x   |
|       7.x       |   3.x   |
|       8.x       |   4.x   |
|       9.x       |   5.x   |

## Installation

You can install the package via composer:

```bash
composer require vemcogroup/laravel-sparkpost-driver
```

The package will automatically register its service provider.

## Usage

**Sparkpost API options**

You can define specific [SparkPost options]
(https://developers.sparkpost.com/api/transmissions/#header-request-body) like `open_tracking`, `click_tracking`, `transactional`

**EU GDPR**

You are able to use the EU endpoint for Europe GDPR compliance by setting the `endpoint` option or the default will be used.

SparkPost (default): `https://api.sparkpost.com/api/v1`
SparkPost EU: `https://api.eu.sparkpost.com/api/v1`

**Guzzle options**

You are able to specify [Guzzle options](http://docs.guzzlephp.org/en/stable/request-options.html) in the SparkPost config section `guzzle`. 

Just add the sparkpost service to your `config/services.php`.

```php
'sparkpost' => [
    'secret' => env('SPARKPOST_SECRET'),

    // optional guzzle specific configuration
    'guzzle' => [
        'verify' => true,
        'decode_content' => true,
        ...
    ],
    'options' => [
        // configure endpoint, if not default
        'endpoint' => env('SPARKPOST_ENDPOINT'),

        // optional Sparkpost API options go here
        'return_path' => 'mail@bounces.domain.com',
        'options' => [
            'open_tracking' => false,
            'click_tracking' => false,
            'transactional' => true,
        ],
    ],
],
```

**API Key**

You will also need to add the SparkPost API Key to your environment file

```php
SPARKPOST_SECRET=__Your_key_here__
```

Finally you need to set your mail driver to SparkPost. You can do this by changing the driver in `config/mail.php`

```php
'driver' => env('MAIL_DRIVER', 'sparkpost'),
```

Or by setting the environment variable `MAIL_DRIVER` in your `.env` file

```php
MAIL_DRIVER=sparkpost
```

**Laravel 7**

If you are using a clean Laravel 7.x installation its important you add the following sparkpost config in `config/mail.php` mailer section.

```php
'mailers' => [
    ...
    'sparkpost' => [
        'transport' => 'sparkpost'
    ],
    ...
],
```
And replace the `MAIL_DRIVER` from .env with `MAIL_MAILER`, make sure to keep the sparkpost config on `config/services.php`.

## Helper functions

### Delete supressions
```php
sparkpost_delete_supression('test@example.com');
```

### Validate single email address
```php
sparkpost_check_email('test@example.com');
```