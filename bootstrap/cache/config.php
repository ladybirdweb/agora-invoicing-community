<?php return array (
  'activitylog' => 
  array (
    'enabled' => true,
    'delete_records_older_than_days' => '180',
    'default_log_name' => 'default',
    'default_auth_driver' => NULL,
    'subject_returns_soft_deleted_models' => false,
    'activity_model' => 'Spatie\\Activitylog\\Models\\Activity',
    'table_name' => 'activity_log',
    'database_connection' => NULL,
  ),
  'app' => 
  array (
    'name' => 'Agora',
    'version' => 'v2.0.0',
    'env' => 'development',
    'debug' => false,
    'url' => 'https://localhost/agora-invoicing-community/public',
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'key' => 'SomeRandomString',
    'cipher' => 'AES-128-CBC',
    'bugsnag_reporting' => true,
    'providers' => 
    array (
      0 => 'App\\Plugins\\Stripe\\ServiceProvider',
      1 => 'App\\Plugins\\Razorpay\\ServiceProvider',
      2 => 'Illuminate\\Auth\\AuthServiceProvider',
      3 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      4 => 'Illuminate\\Bus\\BusServiceProvider',
      5 => 'Illuminate\\Cache\\CacheServiceProvider',
      6 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      7 => 'Illuminate\\Cookie\\CookieServiceProvider',
      8 => 'Illuminate\\Database\\DatabaseServiceProvider',
      9 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      10 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      11 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      12 => 'Illuminate\\Hashing\\HashServiceProvider',
      13 => 'Illuminate\\Mail\\MailServiceProvider',
      14 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      15 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      16 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      17 => 'Illuminate\\Queue\\QueueServiceProvider',
      18 => 'Illuminate\\Redis\\RedisServiceProvider',
      19 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      20 => 'Illuminate\\Session\\SessionServiceProvider',
      21 => 'Illuminate\\Translation\\TranslationServiceProvider',
      22 => 'Illuminate\\Validation\\ValidationServiceProvider',
      23 => 'Illuminate\\View\\ViewServiceProvider',
      24 => 'Bugsnag\\BugsnagLaravel\\BugsnagServiceProvider',
      25 => 'Arcanedev\\LogViewer\\LogViewerServiceProvider',
      26 => 'Torann\\GeoIP\\GeoIPServiceProvider',
      27 => 'Laravel\\Tinker\\TinkerServiceProvider',
      28 => 'App\\Providers\\AppServiceProvider',
      29 => 'App\\Providers\\AuthServiceProvider',
      30 => 'App\\Providers\\EventServiceProvider',
      31 => 'App\\Providers\\HorizonServiceProvider',
      32 => 'App\\Providers\\RouteServiceProvider',
      33 => 'App\\Providers\\CustomValidationProvider',
      34 => 'Collective\\Html\\HtmlServiceProvider',
      35 => 'Barryvdh\\DomPDF\\ServiceProvider',
      36 => 'Yajra\\DataTables\\HtmlServiceProvider',
      37 => 'Yajra\\DataTables\\DataTablesServiceProvider',
      38 => 'Spatie\\Activitylog\\ActivitylogServiceProvider',
      39 => 'Torann\\Currency\\CurrencyServiceProvider',
      40 => 'Devio\\Pipedrive\\PipedriveServiceProvider',
      41 => 'Spatie\\Referer\\RefererServiceProvider',
      42 => 'Cartalyst\\Stripe\\Laravel\\StripeServiceProvider',
      43 => 'PragmaRX\\Google2FALaravel\\ServiceProvider',
      44 => 'Darryldecode\\Cart\\CartServiceProvider',
      45 => 'Creativeorange\\Gravatar\\GravatarServiceProvider',
      46 => 'GrahamCampbell\\Markdown\\MarkdownServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
      'Activity' => 'Spatie\\Activitylog\\ActivitylogFacade',
      'Bugsnag' => 'Bugsnag\\BugsnagLaravel\\Facades\\Bugsnag',
      'Cart' => 'Darryldecode\\Cart\\Facades\\CartFacade',
      'Currency' => 'Torann\\Currency\\Facades\\Currency',
      'DataTables' => 'Yajra\\DataTables\\Facades\\DataTables',
      'Form' => 'Collective\\Html\\FormFacade',
      'GeoIP' => 'Torann\\GeoIP\\Facades\\GeoIP',
      'Google2FA' => 'PragmaRX\\Google2FALaravel\\Facade',
      'HTML' => 'Collective\\Html\\HtmlFacade',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'PDF' => 'Barryvdh\\DomPDF\\Facade',
      'Pipedrive' => 'Devio\\Pipedrive\\PipedriveFacade',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Stripe' => 'Cartalyst\\Stripe\\Laravel\\Facades\\Stripe',
      'Gravatar' => 'Creativeorange\\Gravatar\\Facades\\Gravatar',
      'Markdown' => 'GrahamCampbell\\Markdown\\Facades\\Markdown',
    ),
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
        'table' => 'password_resets',
        'expire' => 120,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'breadcrumbs' => 
  array (
    'view' => 'breadcrumbs::bootstrap3',
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'bugsnag' => 
  array (
    'api_key' => 'aa2ea47afa134faf8aeec634ba6bfb7e',
    'app_type' => NULL,
    'app_version' => NULL,
    'batch_sending' => NULL,
    'endpoint' => NULL,
    'filters' => 
    array (
      0 => 'password',
    ),
    'hostname' => NULL,
    'proxy' => 
    array (
    ),
    'project_root' => NULL,
    'project_root_regex' => NULL,
    'strip_path' => NULL,
    'strip_path_regex' => NULL,
    'query' => true,
    'bindings' => false,
    'release_stage' => NULL,
    'notify_release_stages' => 
    array (
      0 => 'production',
    ),
    'send_code' => true,
    'callbacks' => true,
    'user' => true,
    'logger_notify_level' => NULL,
    'auto_capture_sessions' => false,
    'session_endpoint' => NULL,
    'build_endpoint' => NULL,
    'discard_classes' => NULL,
    'redacted_keys' => NULL,
    'feature_flags' => 
    array (
    ),
  ),
  'cache' => 
  array (
    'default' => 'array',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
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
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => NULL,
        'secret' => NULL,
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
    ),
    'prefix' => 'agora_cache_',
  ),
  'captcha' => 
  array (
    'secret' => '6LfaLjQUAAAAAPXUQ8ERpcgt-q9GxnD1iRIpAUX6',
    'sitekey' => '6LfaLjQUAAAAAIiiihF5q8DC41FH28m5IMo1VNwa',
    'options' => 
    array (
      'timeout' => 30,
    ),
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
  'chunk-upload' => 
  array (
    'storage' => 
    array (
      'chunks' => 'chunks',
      'disk' => 'local',
    ),
    'clear' => 
    array (
      'timestamp' => '-3 HOURS',
      'schedule' => 
      array (
        'enabled' => true,
        'cron' => '25 * * * *',
      ),
    ),
    'chunk' => 
    array (
      'name' => 
      array (
        'use' => 
        array (
          'session' => true,
          'browser' => false,
        ),
      ),
    ),
    'handlers' => 
    array (
      'custom' => 
      array (
      ),
      'override' => 
      array (
      ),
    ),
  ),
  'compile' => 
  array (
    'files' => 
    array (
      0 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\AppServiceProvider.php',
      1 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\EventServiceProvider.php',
      2 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\RouteServiceProvider.php',
    ),
    'providers' => 
    array (
    ),
  ),
  'cors' => 
  array (
    'supportsCredentials' => false,
    'allowedOrigins' => 
    array (
      0 => '*',
    ),
    'allowedHeaders' => 
    array (
      0 => '*',
    ),
    'allowedMethods' => 
    array (
      0 => '*',
    ),
    'maxAge' => 0,
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'currency' => 
  array (
    'default' => 'USD',
    'api_key' => '',
    'driver' => 'database',
    'cache_driver' => NULL,
    'drivers' => 
    array (
      'database' => 
      array (
        'class' => 'Torann\\Currency\\Drivers\\Database',
        'connection' => NULL,
        'table' => 'format_currencies',
      ),
      'filesystem' => 
      array (
        'class' => 'Torann\\Currency\\Drivers\\Filesystem',
        'disk' => NULL,
        'path' => 'currencies.json',
      ),
    ),
    'formatter' => NULL,
    'formatters' => 
    array (
      'php_intl' => 
      array (
        'class' => 'Torann\\Currency\\Formatters\\PHPIntl',
      ),
    ),
  ),
  'custom' => 
  array (
    'razor_key' => '',
    'razor_secret' => '',
    'displayCurrency' => '',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'DB_INSTALL' => '1',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'faveo',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'faveo',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => NULL,
      ),
      'testing' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'faveo',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => 'Innodb',
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'faveo',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'faveo',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
      'horizon' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
        'options' => 
        array (
          'prefix' => 'agora_horizon:',
        ),
      ),
    ),
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'multi_term' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
      'starts_with' => false,
    ),
    'index_column' => 'DT_RowIndex',
    'engines' => 
    array (
      'eloquent' => 'Yajra\\DataTables\\EloquentDataTable',
      'query' => 'Yajra\\DataTables\\QueryDataTable',
      'collection' => 'Yajra\\DataTables\\CollectionDataTable',
      'resource' => 'Yajra\\DataTables\\ApiResourceDataTable',
    ),
    'builders' => 
    array (
    ),
    'nulls_last_sql' => ':column :direction NULLS LAST',
    'error' => NULL,
    'columns' => 
    array (
      'excess' => 
      array (
        0 => 'rn',
        1 => 'row_num',
      ),
      'escape' => '*',
      'raw' => 
      array (
        0 => 'action',
      ),
      'blacklist' => 
      array (
        0 => 'password',
        1 => 'remember_token',
      ),
      'whitelist' => '*',
    ),
    'json' => 
    array (
      'header' => 
      array (
      ),
      'options' => 0,
    ),
  ),
  'datatables-buttons' => 
  array (
    'namespace' => 
    array (
      'base' => 'DataTables',
      'model' => '',
    ),
    'pdf_generator' => 'snappy',
    'snappy' => 
    array (
      'options' => 
      array (
        'no-outline' => true,
        'margin-left' => '0',
        'margin-right' => '0',
        'margin-top' => '10mm',
        'margin-bottom' => '10mm',
      ),
      'orientation' => 'landscape',
    ),
    'parameters' => 
    array (
      'dom' => 'Bfrtip',
      'order' => 
      array (
        0 => 
        array (
          0 => 0,
          1 => 'desc',
        ),
      ),
      'buttons' => 
      array (
        0 => 'create',
        1 => 'export',
        2 => 'print',
        3 => 'reset',
        4 => 'reload',
      ),
    ),
    'generator' => 
    array (
      'columns' => 'id,add your columns,created_at,updated_at',
      'buttons' => 'create,export,print,reset,reload',
      'dom' => 'Bfrtip',
    ),
  ),
  'datatables-fractal' => 
  array (
    'includes' => 'include',
    'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts',
      'font_cache' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts',
      'temp_dir' => 'C:\\Users\\sande\\AppData\\Local\\Temp',
      'chroot' => 'C:\\wamp64\\www\\agora-invoicing-community',
      'allowed_protocols' => 
      array (
        'file://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'http://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'https://' => 
        array (
          'rules' => 
          array (
          ),
        ),
      ),
      'log_output_file' => NULL,
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_paper_orientation' => 'portrait',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => true,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
    'orientation' => 'portrait',
    'defines' => 
    array (
      'DOMPDF_FONT_DIR' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts/',
      'DOMPDF_FONT_CACHE' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts/',
      'DOMPDF_TEMP_DIR' => 'C:\\Users\\sande\\AppData\\Local\\Temp',
      'DOMPDF_CHROOT' => 'C:\\wamp64\\www\\agora-invoicing-community',
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
      'DOMPDF_FONT_HEIGHT_RATIO' => 1.1,
      'DOMPDF_ENABLE_CSS_FLOAT' => false,
      'DOMPDF_ENABLE_HTML5PARSER' => false,
    ),
  ),
  'excel' => 
  array (
    'exports' => 
    array (
      'chunk_size' => 1000,
      'pre_calculate_formulas' => false,
      'strict_null_comparison' => false,
      'csv' => 
      array (
        'delimiter' => ',',
        'enclosure' => '"',
        'line_ending' => '
',
        'use_bom' => false,
        'include_separator_line' => false,
        'excel_compatibility' => false,
        'output_encoding' => '',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'imports' => 
    array (
      'read_only' => true,
      'ignore_empty' => false,
      'heading_row' => 
      array (
        'formatter' => 'slug',
      ),
      'csv' => 
      array (
        'delimiter' => NULL,
        'enclosure' => '"',
        'escape_character' => '\\',
        'contiguous' => false,
        'input_encoding' => 'UTF-8',
      ),
      'properties' => 
      array (
        'creator' => '',
        'lastModifiedBy' => '',
        'title' => '',
        'description' => '',
        'subject' => '',
        'keywords' => '',
        'category' => '',
        'manager' => '',
        'company' => '',
      ),
    ),
    'extension_detector' => 
    array (
      'xlsx' => 'Xlsx',
      'xlsm' => 'Xlsx',
      'xltx' => 'Xlsx',
      'xltm' => 'Xlsx',
      'xls' => 'Xls',
      'xlt' => 'Xls',
      'ods' => 'Ods',
      'ots' => 'Ods',
      'slk' => 'Slk',
      'xml' => 'Xml',
      'gnumeric' => 'Gnumeric',
      'htm' => 'Html',
      'html' => 'Html',
      'csv' => 'Csv',
      'tsv' => 'Csv',
      'pdf' => 'Dompdf',
    ),
    'value_binder' => 
    array (
      'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
    ),
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\cache',
    ),
    'transactions' => 
    array (
      'handler' => 'db',
      'db' => 
      array (
        'connection' => NULL,
      ),
    ),
    'temporary_files' => 
    array (
      'local_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/cache/laravel-excel',
      'remote_disk' => NULL,
      'remote_prefix' => NULL,
      'force_resync_remote' => NULL,
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'slug_whitelist' => '._',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app',
        'throw' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/public',
        'url' => 'https://localhost/agora-invoicing-community/public/storage',
        'visibility' => 'public',
        'throw' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
      ),
    ),
    'links' => 
    array (
      'C:\\wamp64\\www\\agora-invoicing-community\\public\\storage' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/public',
    ),
  ),
  'geoip' => 
  array (
    'log_failures' => true,
    'include_currency' => true,
    'service' => 'ipapi',
    'services' => 
    array (
      'maxmind_database' => 
      array (
        'class' => 'Torann\\GeoIP\\Services\\MaxMindDatabase',
        'database_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/geoip.mmdb',
        'update_url' => 'https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
        'locales' => 
        array (
          0 => 'en',
        ),
      ),
      'maxmind_api' => 
      array (
        'class' => 'Torann\\GeoIP\\Services\\MaxMindWebService',
        'user_id' => NULL,
        'license_key' => NULL,
        'locales' => 
        array (
          0 => 'en',
        ),
      ),
      'ipapi' => 
      array (
        'class' => 'Torann\\GeoIP\\Services\\IPApi',
        'secure' => true,
        'key' => NULL,
        'continent_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/continents.json',
        'lang' => 'en',
      ),
      'ipgeolocation' => 
      array (
        'class' => 'Torann\\GeoIP\\Services\\IPGeoLocation',
        'secure' => true,
        'key' => NULL,
        'continent_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/continents.json',
        'lang' => 'en',
      ),
      'ipdata' => 
      array (
        'class' => 'Torann\\GeoIP\\Services\\IPData',
        'key' => NULL,
        'secure' => true,
      ),
    ),
    'cache' => 'all',
    'cache_tags' => 
    array (
      0 => 'torann-geoip-location',
    ),
    'cache_expires' => 30,
    'default_location' => 
    array (
      'ip' => '127.0.0.0',
      'iso_code' => 'US',
      'country' => 'United States',
      'city' => 'New Haven',
      'state' => 'CT',
      'state_name' => 'Connecticut',
      'postal_code' => '06510',
      'lat' => 41.31,
      'lon' => -72.92,
      'timezone' => 'America/New_York',
      'continent' => 'NA',
      'currency' => 'USD',
      'default' => true,
      'cached' => false,
    ),
  ),
  'google2fa' => 
  array (
    'enabled' => true,
    'lifetime' => 0,
    'keep_alive' => true,
    'auth' => 'auth',
    'guard' => '',
    'session_var' => 'google2fa',
    'otp_input' => 'one_time_password',
    'window' => 1,
    'forbid_old_passwords' => false,
    'otp_secret_column' => 'google2fa_secret',
    'view' => 'google2fa.index',
    'error_messages' => 
    array (
      'wrong_otp' => 'The \'One Time Password\' typed was wrong.',
      'cannot_be_empty' => 'One Time Password cannot be empty.',
      'unknown' => 'An unknown error has occurred. Please try again.',
    ),
    'throw_exceptions' => true,
    'qrcode_image_backend' => 'imagemagick',
  ),
  'gravatar' => 
  array (
    'default' => 
    array (
      'size' => 80,
      'default' => 'identicon',
      'maxRating' => 'g',
      'fallback' => 'mm',
      'secure' => false,
      'maximumRating' => 'g',
      'forceDefault' => false,
      'forceExtension' => 'jpg',
    ),
    'small-secure' => 
    array (
      'size' => 30,
      'secure' => true,
    ),
    'medium' => 
    array (
      'size' => 150,
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
    ),
  ),
  'horizon' => 
  array (
    'domain' => NULL,
    'path' => 'horizon',
    'use' => 'default',
    'prefix' => 'agora_horizon:',
    'middleware' => 
    array (
      0 => 'web',
      1 => 'auth',
      2 => 'admin',
    ),
    'waits' => 
    array (
      'redis:default' => 60,
    ),
    'trim' => 
    array (
      'recent' => 60,
      'pending' => 60,
      'completed' => 60,
      'recent_failed' => 10080,
      'failed' => 10080,
      'monitored' => 10080,
    ),
    'metrics' => 
    array (
      'trim_snapshots' => 
      array (
        'job' => 24,
        'queue' => 24,
      ),
    ),
    'fast_termination' => false,
    'memory_limit' => 64,
    'defaults' => 
    array (
      'supervisor-1' => 
      array (
        'connection' => 'redis',
        'queue' => 
        array (
          0 => 'default',
        ),
        'balance' => 'auto',
        'maxProcesses' => 1,
        'maxTime' => 0,
        'maxJobs' => 0,
        'memory' => 128,
        'tries' => 1,
        'timeout' => 60,
        'nice' => 0,
      ),
    ),
    'environments' => 
    array (
      'production' => 
      array (
        'supervisor-1' => 
        array (
          'connection' => 'redis',
          'queue' => 
          array (
            0 => 'default',
          ),
          'balance' => 'simple',
          'processes' => 10,
          'tries' => 1,
          'nice' => 0,
        ),
      ),
      'local' => 
      array (
        'supervisor-1' => 
        array (
          'connection' => 'redis',
          'queue' => 
          array (
            0 => 'default',
          ),
          'balance' => 'simple',
          'processes' => 3,
          'tries' => 1,
          'nice' => 0,
        ),
      ),
    ),
  ),
  'installer' => 
  array (
    'core' => 
    array (
      'minPhpVersion' => '7.3.0',
    ),
    'requirements' => 
    array (
      'php' => 
      array (
        0 => 'openssl',
        1 => 'pdo',
        2 => 'mbstring',
        3 => 'tokenizer',
        4 => 'JSON',
        5 => 'cURL',
      ),
      'apache' => 
      array (
        0 => 'mod_rewrite',
      ),
    ),
    'permissions' => 
    array (
      'storage/framework/' => '775',
      'storage/logs/' => '775',
      'bootstrap/cache/' => '775',
    ),
    'environment' => 
    array (
      'form' => 
      array (
        'rules' => 
        array (
          'environment' => 'required|string|max:50',
          'environment_custom' => 'required_if:environment,other|max:50',
          'app_log_level' => 'required|string|max:50',
          'database_connection' => 'required|string|max:50',
          'database_hostname' => 'required|string|max:50',
          'database_port' => 'required|numeric',
          'database_name' => 'required|string|max:50',
          'database_username' => 'required|string|max:50',
          'broadcast_driver' => 'required|string|max:50',
          'cache_driver' => 'required|string|max:50',
          'session_driver' => 'required|string|max:50',
          'queue_driver' => 'required|string|max:50',
          'redis_hostname' => 'required|string|max:50',
          'redis_password' => 'required|string|max:50',
          'redis_port' => 'required|numeric',
          'mail_driver' => 'required|string|max:50',
          'mail_host' => 'required|string|max:50',
          'mail_port' => 'required|string|max:50',
          'mail_username' => 'required|string|max:50',
          'mail_password' => 'required|string|max:50',
          'mail_encryption' => 'required|string|max:50',
          'pusher_app_id' => 'max:50',
          'pusher_app_key' => 'max:50',
          'pusher_app_secret' => 'max:50',
        ),
      ),
    ),
    'installed' => 
    array (
      'redirectOptions' => 
      array (
        'route' => 
        array (
          'name' => 'welcome',
          'data' => 
          array (
          ),
        ),
        'abort' => 
        array (
          'type' => '404',
        ),
        'dump' => 
        array (
          'data' => 'Dumping a not found message.',
        ),
      ),
    ),
    'installedAlreadyAction' => '',
    'updaterEnabled' => 'true',
  ),
  'log-viewer' => 
  array (
    'storage-path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs',
    'pattern' => 
    array (
      'prefix' => 'laravel-',
      'date' => '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]',
      'extension' => '.log',
    ),
    'locale' => 'auto',
    'theme' => 'bootstrap-4',
    'route' => 
    array (
      'enabled' => true,
      'attributes' => 
      array (
        'prefix' => 'log-viewer',
        'middleware' => NULL,
      ),
    ),
    'per-page' => 30,
    'download' => 
    array (
      'prefix' => 'laravel-',
      'extension' => 'log',
    ),
    'menu' => 
    array (
      'filter-route' => 'log-viewer::logs.filter',
      'icons-enabled' => true,
    ),
    'icons' => 
    array (
      'all' => 'fa fa-fw fa-list',
      'emergency' => 'fa fa-fw fa-bug',
      'alert' => 'fa fa-fw fa-bullhorn',
      'critical' => 'fa fa-fw fa-heartbeat',
      'error' => 'fa fa-fw fa-times-circle',
      'warning' => 'fa fa-fw fa-exclamation-triangle',
      'notice' => 'fa fa-fw fa-exclamation-circle',
      'info' => 'fa fa-fw fa-info-circle',
      'debug' => 'fa fa-fw fa-life-ring',
    ),
    'colors' => 
    array (
      'levels' => 
      array (
        'empty' => '#D1D1D1',
        'all' => '#8A8A8A',
        'emergency' => '#B71C1C',
        'alert' => '#D32F2F',
        'critical' => '#F44336',
        'error' => '#FF5722',
        'warning' => '#FF9100',
        'notice' => '#4CAF50',
        'info' => '#1976D2',
        'debug' => '#90CAF9',
      ),
    ),
    'highlight' => 
    array (
      0 => '^#\\d+',
      1 => '^Stack trace:',
    ),
    'facade' => 'LogViewer',
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 'null',
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 7,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => '587',
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'host' => 'smtp.mailtrap.io',
        'port' => '2525',
        'encryption' => NULL,
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => NULL,
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'mailgun' => 
      array (
        'transport' => 'mailgun',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Example',
    ),
    'encryption' => '',
    'username' => '',
    'password' => '',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => true,
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'C:\\wamp64\\www\\agora-invoicing-community\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'mailchimp' => 
  array (
    'apikey' => '',
  ),
  'markdown' => 
  array (
    'views' => true,
    'extensions' => 
    array (
    ),
    'renderer' => 
    array (
      'block_separator' => '
',
      'inner_separator' => '
',
      'soft_break' => '
',
    ),
    'commonmark' => 
    array (
      'enable_em' => true,
      'enable_strong' => true,
      'use_asterisk' => true,
      'use_underscore' => true,
      'unordered_list_markers' => 
      array (
        0 => '-',
        1 => '+',
        2 => '*',
      ),
    ),
    'html_input' => 'strip',
    'allow_unsafe_links' => true,
    'max_nesting_level' => 9223372036854775807,
    'slug_normalizer' => 
    array (
      'max_length' => 255,
      'unique' => 'document',
    ),
    'enable_em' => true,
    'enable_strong' => true,
    'use_asterisk' => true,
    'use_underscore' => true,
  ),
  'queue' => 
  array (
    'default' => 'sync',
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
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => NULL,
        'secret' => NULL,
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'referer' => 
  array (
    'session_key' => 'referer',
    'sources' => 
    array (
      0 => 'Spatie\\Referer\\Sources\\UtmSource',
      1 => 'Spatie\\Referer\\Sources\\RequestHeader',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
      'scheme' => 'https',
    ),
    'mandrill' => 
    array (
      'secret' => '',
    ),
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'User',
      'secret' => '',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'agora_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
  ),
  'shopping_cart' => 
  array (
    'format_numbers' => false,
    'decimals' => 0,
    'dec_point' => '.',
    'thousands_sep' => ',',
    'storage' => NULL,
    'events' => NULL,
  ),
  'tracker' => 
  array (
    'enabled' => true,
    'cache_enabled' => true,
    'use_middleware' => true,
    'do_not_track_robots' => false,
    'do_not_track_environments' => 
    array (
    ),
    'do_not_track_routes' => 
    array (
      0 => 'tracker.stats.*',
    ),
    'do_not_track_paths' => 
    array (
      0 => 'api/*',
    ),
    'do_not_track_ips' => 
    array (
      0 => '127.0.0.0/24',
    ),
    'log_untrackable_sessions' => true,
    'log_enabled' => false,
    'console_log_enabled' => false,
    'log_sql_queries' => false,
    'connection' => 'tracker',
    'do_not_log_sql_queries_connections' => 
    array (
      0 => 'tracker',
    ),
    'geoip_database_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\config/geoip',
    'log_sql_queries_bindings' => false,
    'log_events' => false,
    'log_only_events' => 
    array (
    ),
    'id_columns_names' => 
    array (
      0 => 'id',
    ),
    'do_not_log_events' => 
    array (
      0 => 'illuminate.log',
      1 => 'eloquent.*',
      2 => 'router.*',
      3 => 'composing: *',
      4 => 'creating: *',
    ),
    'log_geoip' => false,
    'log_user_agents' => false,
    'log_users' => false,
    'log_devices' => false,
    'log_languages' => false,
    'log_referers' => false,
    'log_paths' => false,
    'log_queries' => false,
    'log_routes' => false,
    'log_exceptions' => false,
    'store_cookie_tracker' => false,
    'tracker_cookie_name' => 'please_change_this_cookie_name',
    'tracker_session_name' => 'tracker_session',
    'user_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\User',
    'session_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Session',
    'log_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Log',
    'path_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Path',
    'query_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Query',
    'query_argument_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\QueryArgument',
    'agent_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Agent',
    'device_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Device',
    'cookie_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Cookie',
    'domain_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Domain',
    'referer_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Referer',
    'referer_search_term_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\RefererSearchTerm',
    'route_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Route',
    'route_path_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\RoutePath',
    'route_path_parameter_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\RoutePathParameter',
    'error_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Error',
    'geoip_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\GeoIp',
    'sql_query_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\SqlQuery',
    'sql_query_binding_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\SqlQueryBinding',
    'sql_query_binding_parameter_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\SqlQueryBindingParameter',
    'sql_query_log_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\SqlQueryLog',
    'connection_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Connection',
    'event_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Event',
    'event_log_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\EventLog',
    'system_class_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\SystemClass',
    'language_model' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Models\\Language',
    'authentication_ioc_binding' => 
    array (
      0 => 'auth',
    ),
    'authentication_guards' => 
    array (
    ),
    'authenticated_check_method' => 'check',
    'authenticated_user_method' => 'user',
    'authenticated_user_id_column' => 'id',
    'authenticated_user_username_column' => 'email',
    'stats_panel_enabled' => false,
    'stats_routes_before_filter' => '',
    'stats_routes_after_filter' => '',
    'stats_routes_middleware' => 'web',
    'stats_template_path' => '/templates/sb-admin-2',
    'stats_base_uri' => 'stats',
    'stats_layout' => 'pragmarx/tracker::layout',
    'stats_controllers_namespace' => 'PragmaRX\\Tracker\\Vendor\\Laravel\\Controllers',
  ),
  'transform' => 
  array (
    'cart' => 
    array (
      'group' => '{{group}}',
      'name' => '{{name}}',
      'price' => '{{price}}',
      'price-description' => '{{price-description}}',
      'feature' => '<li>{{feature}}</li>',
      'subscription' => '{{subscription}}',
      'url' => '{{url}}',
    ),
    'welcome_mail' => 
    array (
      'website_url' => '{{website_url}}',
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
      'serialkeyurl' => '{{serialkeyurl}}',
      'knowledge_base' => '{{knowledge_base}}',
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
      'contact-us' => '{{contact-us}}',
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
    'sales_manager_email' => 
    array (
      'name' => '{{name}}',
      'manager_first_name' => '{{manager_first_name}}',
      'manager_last_name' => '{{manager_last_name}}',
      'manager_email' => '{{manager_email}}',
      'manager_code' => '{{manager_code}}',
      'manager_mobile' => '{{manager_mobile}}',
      'manager_skype' => '{{manager_skype}}',
    ),
    'account_manager_email' => 
    array (
      'name' => '{{name}}',
      'manager_first_name' => '{{manager_first_name}}',
      'manager_last_name' => '{{manager_last_name}}',
      'manager_email' => '{{manager_email}}',
      'manager_code' => '{{manager_code}}',
      'manager_mobile' => '{{manager_mobile}}',
      'manager_skype' => '{{manager_skype}}',
    ),
    'password_mail' => 
    array (
      'name' => '{{name}}',
      'username' => '{{username}}',
      'password' => '{{password}}',
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\wamp64\\www\\agora-invoicing-community\\resources\\views',
    ),
    'compiled' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework\\views',
  ),
  'visitortracker' => 
  array (
    'dont_record' => 
    array (
    ),
    'dont_record_geoip' => 
    array (
    ),
    'dont_track_authenticated_users' => true,
    'dont_track_anonymous_users' => false,
    'dont_track_users' => 
    array (
    ),
    'login_attempt' => 
    array (
      'url' => '/login',
      'method' => 'POST',
      'is_ajax' => false,
    ),
    'geoip_on' => false,
    'geoip_driver' => 'ipstack.com',
    'ipstack_key' => '',
    'users_table' => 'users',
    'layout' => 'themes.default1.common.stats',
    'section_content' => 'content',
    'results_per_page' => 15,
    'datetime_format' => 'd M Y, H:i:s',
    'timezone' => 'Asia/Kolkata',
  ),
  'flare' => 
  array (
    'key' => NULL,
    'flare_middleware' => 
    array (
      0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
      1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
      2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
      3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
      4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
      5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => 
      array (
        'maximum_number_of_collected_logs' => 200,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => 
      array (
        'maximum_number_of_collected_queries' => 200,
        'report_query_bindings' => true,
      ),
      'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => 
      array (
        'max_chained_job_reporting_depth' => 5,
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => 
      array (
        'censor_fields' => 
        array (
          0 => 'password',
          1 => 'password_confirmation',
        ),
      ),
      'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => 
      array (
        'headers' => 
        array (
          0 => 'API-KEY',
        ),
      ),
    ),
    'send_logs_as_events' => true,
  ),
  'ignition' => 
  array (
    'editor' => 'phpstorm',
    'theme' => 'auto',
    'enable_share_button' => true,
    'register_commands' => false,
    'solution_providers' => 
    array (
      0 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\BadMethodCallSolutionProvider',
      1 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\MergeConflictSolutionProvider',
      2 => 'Spatie\\Ignition\\Solutions\\SolutionProviders\\UndefinedPropertySolutionProvider',
      3 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\IncorrectValetDbCredentialsSolutionProvider',
      4 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingAppKeySolutionProvider',
      5 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\DefaultDbNameSolutionProvider',
      6 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\TableNotFoundSolutionProvider',
      7 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingImportSolutionProvider',
      8 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\InvalidRouteActionSolutionProvider',
      9 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\ViewNotFoundSolutionProvider',
      10 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\RunningLaravelDuskInProductionProvider',
      11 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingColumnSolutionProvider',
      12 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UnknownValidationSolutionProvider',
      13 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingMixManifestSolutionProvider',
      14 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingViteManifestSolutionProvider',
      15 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\MissingLivewireComponentSolutionProvider',
      16 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\UndefinedViewVariableSolutionProvider',
      17 => 'Spatie\\LaravelIgnition\\Solutions\\SolutionProviders\\GenericLaravelExceptionSolutionProvider',
    ),
    'ignored_solution_providers' => 
    array (
    ),
    'enable_runnable_solutions' => false,
    'remote_sites_path' => 'C:\\wamp64\\www\\agora-invoicing-community',
    'local_sites_path' => '',
    'housekeeping_endpoint_prefix' => '_ignition',
    'settings_file_path' => '',
  ),
  'datatables-html' => 
  array (
    'namespace' => 'LaravelDataTables',
    'table' => 
    array (
      'class' => 'table',
      'id' => 'dataTableBuilder',
    ),
    'callback' => 
    array (
      0 => '$',
      1 => '$.',
      2 => 'function',
    ),
    'script' => 'datatables::script',
    'editor' => 'datatables::editor',
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
