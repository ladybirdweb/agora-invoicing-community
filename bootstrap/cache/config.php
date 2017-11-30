<?php return array (
  'app' => 
  array (
    'env' => 'developing',
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'base64:TcbANLN9eGES4/14+pl0UWp3+eNbE6rlZMGQo8oQRlQ=',
    'cipher' => 'AES-256-CBC',
    'log' => 'daily',
    'providers' => 
    array (
      0 => 'App\\Plugins\\Paypal\\ServiceProvider',
      1 => 'App\\Plugins\\Ccavanue\\ServiceProvider',
      2 => 'Illuminate\\Auth\\AuthServiceProvider',
      3 => 'Illuminate\\Bus\\BusServiceProvider',
      4 => 'Illuminate\\Cache\\CacheServiceProvider',
      5 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      11 => 'Illuminate\\Hashing\\HashServiceProvider',
      12 => 'Illuminate\\Mail\\MailServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Thomaswelton\\LaravelGravatar\\LaravelGravatarServiceProvider',
      23 => 'Chumper\\Datatable\\DatatableServiceProvider',
      24 => 'Collective\\Html\\HtmlServiceProvider',
      25 => 'Darryldecode\\Cart\\CartServiceProvider',
      26 => 'Barryvdh\\DomPDF\\ServiceProvider',
      27 => 'App\\Providers\\AppServiceProvider',
      28 => 'App\\Providers\\EventServiceProvider',
      29 => 'App\\Providers\\RouteServiceProvider',
      30 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      31 => 'Mailchimp\\MailchimpServiceProvider',
      32 => 'Torann\\GeoIP\\GeoIPServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'Inspiring' => 'Illuminate\\Foundation\\Inspiring',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Form' => 'Collective\\Html\\FormFacade',
      'HTML' => 'Collective\\Html\\HtmlFacade',
      'Form-ill' => 'Illuminate\\Html\\FormFacade',
      'HTML-ill' => 'Illuminate\\Html\\HtmlFacade',
      'Gravatar' => 'Thomaswelton\\LaravelGravatar\\Facades\\Gravatar',
      'Datatable' => 'Chumper\\Datatable\\Facades\\DatatableFacade',
      'Cart' => 'Darryldecode\\Cart\\Facades\\CartFacade',
      'MC' => 'Mailchimp\\MailchimpFacade',
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
      'GeoIP' => 'Torann\\GeoIP\\GeoIPFacade',
    ),
    'version' => '1.0',
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'breadcrumbs' => 
  array (
    'view' => 'breadcrumbs::bootstrap3',
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage/framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'chumper' => 
  array (
    'datatable' => 
    array (
      'table' => 
      array (
        'class' => 'table table-responsive table-bordered table-striped dataTable mailbox-messages',
        'id' => '',
        'options' => 
        array (
          'pagingType' => 'simple_numbers',
          'bProcessing' => true,
          'columnDefs' => 
          array (
            0 => 
            array (
              'targets' => 0,
              'orderable' => false,
            ),
          ),
        ),
        'callbacks' => 
        array (
          'fnDrawCallback' => 'function( oSettings ) {
                    $(".box-body").css({"opacity": "1"});
                    $("#blur-bg").css({"opacity": "1", "z-index": "99999"});
                }',
          'fnPreDrawCallback' => 'function(oSettings, json) {
                    $(".box-body").css({"opacity":"0.3"});
                }',
        ),
        'noScript' => false,
        'table_view' => 'chumper.datatable::template',
        'script_view' => 'chumper.datatable::javascript',
      ),
      'engine' => 
      array (
        'exactWordSearch' => false,
      ),
      'classmap' => 
      array (
        'CollectionEngine' => 'Chumper\\Datatable\\Engines\\CollectionEngine',
        'QueryEngine' => 'Chumper\\Datatable\\Engines\\QueryEngine',
        'Table' => 'Chumper\\Datatable\\Table',
      ),
    ),
  ),
  'compile' => 
  array (
    'files' => 
    array (
      0 => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\app\\Providers\\AppServiceProvider.php',
      1 => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\app\\Providers\\EventServiceProvider.php',
      2 => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\app\\Providers\\RouteServiceProvider.php',
    ),
    'providers' => 
    array (
    ),
  ),
  'database' => 
  array (
    'fetch' => 8,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage/database.sqlite',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'agora',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'database' => 'agora',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'database' => 'agora',
        'username' => 'root',
        'password' => '',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
      ),
    ),
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => 
    array (
      'DOMPDF_FONT_DIR' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage\\fonts/',
      'DOMPDF_FONT_CACHE' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage\\fonts/',
      'DOMPDF_TEMP_DIR' => 'C:\\Users\\arind\\AppData\\Local\\Temp',
      'DOMPDF_CHROOT' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing',
      'DOMPDF_UNICODE_ENABLED' => true,
      'DOMPDF_ENABLE_FONTSUBSETTING' => false,
      'DOMPDF_PDF_BACKEND' => 'CPDF',
      'DOMPDF_DEFAULT_MEDIA_TYPE' => 'screen',
      'DOMPDF_DEFAULT_PAPER_SIZE' => 'a4',
      'DOMPDF_DEFAULT_FONT' => 'serif',
      'DOMPDF_DPI' => 96,
      'DOMPDF_ENABLE_PHP' => false,
      'DOMPDF_ENABLE_JAVASCRIPT' => true,
      'DOMPDF_ENABLE_REMOTE' => true,
      'DOMPDF_FONT_HEIGHT_RATIO' => 1.1000000000000001,
      'DOMPDF_ENABLE_CSS_FLOAT' => false,
      'DOMPDF_ENABLE_HTML5PARSER' => false,
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage/app',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
      'rackspace' => 
      array (
        'driver' => 'rackspace',
        'username' => 'your-username',
        'key' => 'your-key',
        'container' => 'your-container',
        'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
        'region' => 'IAD',
        'url_type' => 'publicURL',
      ),
    ),
  ),
  'geoip' => 
  array (
    'service' => 'maxmind',
    'maxmind' => 
    array (
      'type' => 'database',
      'user_id' => NULL,
      'license_key' => NULL,
      'database_path' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage\\app/geoip.mmdb',
      'update_url' => 'https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
    ),
    'default_location' => 
    array (
      'ip' => '122.172.180.5',
      'isoCode' => 'IN',
      'country' => 'India',
      'city' => 'Bengaluru',
      'state' => 'KA',
      'postal_code' => 560076,
      'lat' => 12.9833,
      'lon' => 77.583299999999994,
      'timezone' => 'Asia/Kolkata',
      'continent' => 'AS',
      'default' => false,
    ),
  ),
  'gravatar' => 
  array (
    'size' => 80,
    'default' => 'identicon',
    'maxRating' => 'g',
  ),
  'mail' => 
  array (
    'driver' => 'mail',
    'host' => '',
    'port' => '',
    'from' => 
    array (
      'address' => 'info@faveohelpdesk.com',
      'name' => 'Faveo Helpdesk',
    ),
    'encryption' => '',
    'username' => '',
    'password' => '',
    'sendmail' => '/usr/sbin/sendmail -bs',
  ),
  'mailchimp' => 
  array (
    'apikey' => '',
  ),
  'queue' => 
  array (
    'default' => 'syncDB_TYPE=mysql',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'expire' => 60,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'ttr' => 60,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'queue' => 'your-queue-url',
        'region' => 'us-east-1',
      ),
      'iron' => 
      array (
        'driver' => 'iron',
        'host' => 'mq-aws-us-east-1.iron.io',
        'token' => 'your-token',
        'project' => 'your-project-id',
        'queue' => 'your-queue-name',
        'encrypt' => true,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'queue' => 'default',
        'expire' => 60,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => '',
      'secret' => '',
    ),
    'mandrill' => 
    array (
      'secret' => '',
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => '',
      'secret' => '',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
  ),
  'transform' => 
  array (
    'cart' => 
    array (
      'name' => '{{name}}',
      'price' => '{{price}}',
      'feature' => '<li>{{feature}}</li>',
      'subscription' => '{{subscription}}',
      'url' => '{{url}}',
    ),
    'welcome_mail' => 
    array (
      'name' => '{{name}}',
      'username' => '{{username}}',
      'password' => '{{password}}',
      'url' => '{{url}}',
    ),
    'order_mail' => 
    array (
      'name' => '{{name}}',
      'downloadurl' => '{{downloadurl}}',
      'invoiceurl' => '{{invoiceurl}}',
      'product' => '{{product}}',
      'number' => '{{number}}',
      'expiry' => '{{expiry}}',
      'url' => '{{url}}',
    ),
    'invoice_mail' => 
    array (
      'name' => '{{name}}',
      'number' => '{{number}}',
      'address' => '{{address}}',
      'invoiceurl' => '{{invoiceurl}}',
      'total' => '{{total}}',
      'content' => '{{content}}',
      'currency' => '{{currency}}',
    ),
    'forgot_password_mail' => 
    array (
      'name' => '{{name}}',
      'url' => '{{url}}',
    ),
    'subscription_going_to_end_mail' => 
    array (
      'name' => '{{name}}',
      'number' => '{{number}}',
      'product' => '{{product}}',
      'expiry' => '{{expiry}}',
      'url' => '{{url}}',
    ),
    'subscription_over_mail' => 
    array (
      'name' => '{{name}}',
      'number' => '{{number}}',
      'product' => '{{product}}',
      'expiry' => '{{expiry}}',
      'url' => '{{url}}',
    ),
    'manager_email' => 
    array (
      'name' => '{{name}}',
      'manager_first_name' => '{{manager_first_name}}',
      'manager_last_name' => '{{manager_last_name}}',
      'manager_email' => '{{manager_email}}',
      'manager_code' => '{{manager_code}}',
      'manager_mobile' => '{{manager_mobile}}',
      'manager_skype' => '{{manager_skype}}',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\resources\\views',
    ),
    'compiled' => 'C:\\wamp64\\www\\agorabilling\\agorainvoicing\\storage\\framework\\views',
  ),
  'shopping_cart' => 
  array (
    'format_numbers' => false,
    'decimals' => 0,
    'dec_point' => '.',
    'thousands_sep' => ',',
  ),
);
