# IronMQ Laravel Queue Driver

This package provides a IronMQ (~4.0 SDK) driver for the Laravel queue system and matches the driver that was found in Laravel 5.1.

## Installation
- composer require laravelcollective/iron-queue
- Add `Collective\IronQueue\IronQueueServiceProvider::class` to your `app.php` configuration file.
- Configure your `iron` queue driver in your `config/queue.php` the same as it would have been configured for Laravel 5.1.

Sample Configuration:

```php
'iron' => [
    'driver'  => 'iron',
    'host'    => 'mq-aws-us-east-1-1.iron.io',
    'token'   => 'your-token',
    'project' => 'your-project-id',
    'queue'   => 'your-queue-name',
    'encrypt' => true,
    'timeout' => 60
],
```
