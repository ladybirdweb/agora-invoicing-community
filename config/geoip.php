<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Service
      |--------------------------------------------------------------------------
      |
      | Current only supports 'maxmind'.
      |
     */

    'service' => 'maxmind',
    /*
      |--------------------------------------------------------------------------
      | Services settings
      |--------------------------------------------------------------------------
      |
      | Service specific settings.
      |
     */
    'maxmind' => [
        'type'          => env('GEOIP_DRIVER', 'database'), // database or web_service
        'user_id'       => env('GEOIP_USER_ID'),
        'license_key'   => env('GEOIP_LICENSE_KEY'),
        'database_path' => storage_path('app/geoip.mmdb'),
        'update_url'    => 'https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
    ],
    /*
      |--------------------------------------------------------------------------
      | Default Location
      |--------------------------------------------------------------------------
      |
      | Return when a location is not found.
      |
     */

    'default_location' => [
        'ip'          => '122.172.180.5',
        'isoCode'     => 'IN',
        'country'     => 'India',
        'city'        => 'Bengaluru',
        'state'       => 'KA',
        'postal_code' => 560076,
        'lat'         => 12.9833,
        'lon'         => 77.5833,
        'timezone'    => 'Asia/Kolkata',
        'continent'   => 'AS',
        'default'     => false,
    ],
];
