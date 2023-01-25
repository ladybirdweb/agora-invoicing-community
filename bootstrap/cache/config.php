<?php

return [
    'activitylog' => [
        'enabled' => true,
        'delete_records_older_than_days' => '180',
        'default_log_name' => 'default',
        'default_auth_driver' => null,
        'subject_returns_soft_deleted_models' => false,
        'activity_model' => 'Spatie\\Activitylog\\Models\\Activity',
        'table_name' => 'activity_log',
        'database_connection' => null,
    ],
    'app' => [
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
        'providers' => [
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
        ],
        'aliases' => [
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
        ],
    ],
    'auth' => [
        'defaults' => [
            'guard' => 'web',
            'passwords' => 'users',
        ],
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
        ],
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => 'App\\User',
            ],
        ],
        'passwords' => [
            'users' => [
                'provider' => 'users',
                'table' => 'password_resets',
                'expire' => 120,
                'throttle' => 60,
            ],
        ],
        'password_timeout' => 10800,
    ],
    'breadcrumbs' => [
        'view' => 'breadcrumbs::bootstrap3',
    ],
    'broadcasting' => [
        'default' => 'log',
        'connections' => [
            'pusher' => [
                'driver' => 'pusher',
                'key' => '',
                'secret' => '',
                'app_id' => '',
                'options' => [
                    'host' => 'api-mt1.pusher.com',
                    'port' => 443,
                    'scheme' => 'https',
                    'encrypted' => true,
                    'useTLS' => true,
                ],
                'client_options' => [
                ],
            ],
            'ably' => [
                'driver' => 'ably',
                'key' => null,
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
            ],
            'log' => [
                'driver' => 'log',
            ],
            'null' => [
                'driver' => 'null',
            ],
        ],
    ],
    'bugsnag' => [
        'api_key' => 'aa2ea47afa134faf8aeec634ba6bfb7e',
        'app_type' => null,
        'app_version' => null,
        'batch_sending' => null,
        'endpoint' => null,
        'filters' => [
            0 => 'password',
        ],
        'hostname' => null,
        'proxy' => [
        ],
        'project_root' => null,
        'project_root_regex' => null,
        'strip_path' => null,
        'strip_path_regex' => null,
        'query' => true,
        'bindings' => false,
        'release_stage' => null,
        'notify_release_stages' => [
            0 => 'production',
        ],
        'send_code' => true,
        'callbacks' => true,
        'user' => true,
        'logger_notify_level' => null,
        'auto_capture_sessions' => false,
        'session_endpoint' => null,
        'build_endpoint' => null,
        'discard_classes' => null,
        'redacted_keys' => null,
        'feature_flags' => [
        ],
    ],
    'cache' => [
        'default' => 'array',
        'stores' => [
            'apc' => [
                'driver' => 'apc',
            ],
            'array' => [
                'driver' => 'array',
                'serialize' => false,
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'cache',
                'connection' => null,
                'lock_connection' => null,
            ],
            'file' => [
                'driver' => 'file',
                'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/cache/data',
            ],
            'memcached' => [
                'driver' => 'memcached',
                'persistent_id' => null,
                'sasl' => [
                    0 => null,
                    1 => null,
                ],
                'options' => [
                ],
                'servers' => [
                    0 => [
                        'host' => '127.0.0.1',
                        'port' => 11211,
                        'weight' => 100,
                    ],
                ],
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
                'lock_connection' => 'default',
            ],
            'dynamodb' => [
                'driver' => 'dynamodb',
                'key' => null,
                'secret' => null,
                'region' => 'us-east-1',
                'table' => 'cache',
                'endpoint' => null,
            ],
            'octane' => [
                'driver' => 'octane',
            ],
        ],
        'prefix' => 'agora_cache_',
    ],
    'captcha' => [
        'secret' => '6LfaLjQUAAAAAPXUQ8ERpcgt-q9GxnD1iRIpAUX6',
        'sitekey' => '6LfaLjQUAAAAAIiiihF5q8DC41FH28m5IMo1VNwa',
        'options' => [
            'timeout' => 30,
        ],
    ],
    'chumper' => [
        'datatable' => [
            'table' => [
                'class' => 'table table-responsive table-bordered table-striped dataTable mailbox-messages',
                'id' => '',
                'options' => [
                    'pagingType' => 'simple_numbers',
                    'bProcessing' => true,
                    'columnDefs' => [
                        0 => [
                            'targets' => 0,
                            'orderable' => false,
                        ],
                    ],
                ],
                'callbacks' => [
                    'fnDrawCallback' => 'function( oSettings ) {
                    $(".box-body").css({"opacity": "1"});
                    $("#blur-bg").css({"opacity": "1", "z-index": "99999"});
                }',
                    'fnPreDrawCallback' => 'function(oSettings, json) {
                    $(".box-body").css({"opacity":"0.3"});
                }',
                ],
                'noScript' => false,
                'table_view' => 'chumper.datatable::template',
                'script_view' => 'chumper.datatable::javascript',
            ],
            'engine' => [
                'exactWordSearch' => false,
            ],
            'classmap' => [
                'CollectionEngine' => 'Chumper\\Datatable\\Engines\\CollectionEngine',
                'QueryEngine' => 'Chumper\\Datatable\\Engines\\QueryEngine',
                'Table' => 'Chumper\\Datatable\\Table',
            ],
        ],
    ],
    'chunk-upload' => [
        'storage' => [
            'chunks' => 'chunks',
            'disk' => 'local',
        ],
        'clear' => [
            'timestamp' => '-3 HOURS',
            'schedule' => [
                'enabled' => true,
                'cron' => '25 * * * *',
            ],
        ],
        'chunk' => [
            'name' => [
                'use' => [
                    'session' => true,
                    'browser' => false,
                ],
            ],
        ],
        'handlers' => [
            'custom' => [
            ],
            'override' => [
            ],
        ],
    ],
    'compile' => [
        'files' => [
            0 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\AppServiceProvider.php',
            1 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\EventServiceProvider.php',
            2 => 'C:\\wamp64\\www\\agora-invoicing-community\\app\\Providers\\RouteServiceProvider.php',
        ],
        'providers' => [
        ],
    ],
    'cors' => [
        'supportsCredentials' => false,
        'allowedOrigins' => [
            0 => '*',
        ],
        'allowedHeaders' => [
            0 => '*',
        ],
        'allowedMethods' => [
            0 => '*',
        ],
        'maxAge' => 0,
        'paths' => [
            0 => 'api/*',
            1 => 'sanctum/csrf-cookie',
        ],
        'allowed_methods' => [
            0 => '*',
        ],
        'allowed_origins' => [
            0 => '*',
        ],
        'allowed_origins_patterns' => [
        ],
        'allowed_headers' => [
            0 => '*',
        ],
        'exposed_headers' => [
        ],
        'max_age' => 0,
        'supports_credentials' => false,
    ],
    'currency' => [
        'default' => 'USD',
        'api_key' => '',
        'driver' => 'database',
        'cache_driver' => null,
        'drivers' => [
            'database' => [
                'class' => 'Torann\\Currency\\Drivers\\Database',
                'connection' => null,
                'table' => 'format_currencies',
            ],
            'filesystem' => [
                'class' => 'Torann\\Currency\\Drivers\\Filesystem',
                'disk' => null,
                'path' => 'currencies.json',
            ],
        ],
        'formatter' => null,
        'formatters' => [
            'php_intl' => [
                'class' => 'Torann\\Currency\\Formatters\\PHPIntl',
            ],
        ],
    ],
    'custom' => [
        'razor_key' => '',
        'razor_secret' => '',
        'displayCurrency' => '',
    ],
    'database' => [
        'default' => 'mysql',
        'DB_INSTALL' => '1',
        'connections' => [
            'sqlite' => [
                'driver' => 'sqlite',
                'database' => 'faveo',
                'prefix' => '',
            ],
            'mysql' => [
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
                'engine' => null,
            ],
            'testing' => [
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
            ],
            'pgsql' => [
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
            ],
            'sqlsrv' => [
                'driver' => 'sqlsrv',
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'faveo',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'prefix' => '',
            ],
        ],
        'migrations' => 'migrations',
        'redis' => [
            'client' => 'predis',
            'default' => [
                'host' => '127.0.0.1',
                'password' => null,
                'port' => '6379',
                'database' => 0,
            ],
            'horizon' => [
                'host' => '127.0.0.1',
                'password' => null,
                'port' => '6379',
                'database' => 0,
                'options' => [
                    'prefix' => 'agora_horizon:',
                ],
            ],
        ],
    ],
    'datatables' => [
        'search' => [
            'smart' => true,
            'multi_term' => true,
            'case_insensitive' => true,
            'use_wildcards' => false,
            'starts_with' => false,
        ],
        'index_column' => 'DT_RowIndex',
        'engines' => [
            'eloquent' => 'Yajra\\DataTables\\EloquentDataTable',
            'query' => 'Yajra\\DataTables\\QueryDataTable',
            'collection' => 'Yajra\\DataTables\\CollectionDataTable',
            'resource' => 'Yajra\\DataTables\\ApiResourceDataTable',
        ],
        'builders' => [
        ],
        'nulls_last_sql' => ':column :direction NULLS LAST',
        'error' => null,
        'columns' => [
            'excess' => [
                0 => 'rn',
                1 => 'row_num',
            ],
            'escape' => '*',
            'raw' => [
                0 => 'action',
            ],
            'blacklist' => [
                0 => 'password',
                1 => 'remember_token',
            ],
            'whitelist' => '*',
        ],
        'json' => [
            'header' => [
            ],
            'options' => 0,
        ],
    ],
    'datatables-buttons' => [
        'namespace' => [
            'base' => 'DataTables',
            'model' => '',
        ],
        'pdf_generator' => 'snappy',
        'snappy' => [
            'options' => [
                'no-outline' => true,
                'margin-left' => '0',
                'margin-right' => '0',
                'margin-top' => '10mm',
                'margin-bottom' => '10mm',
            ],
            'orientation' => 'landscape',
        ],
        'parameters' => [
            'dom' => 'Bfrtip',
            'order' => [
                0 => [
                    0 => 0,
                    1 => 'desc',
                ],
            ],
            'buttons' => [
                0 => 'create',
                1 => 'export',
                2 => 'print',
                3 => 'reset',
                4 => 'reload',
            ],
        ],
        'generator' => [
            'columns' => 'id,add your columns,created_at,updated_at',
            'buttons' => 'create,export,print,reset,reload',
            'dom' => 'Bfrtip',
        ],
    ],
    'datatables-fractal' => [
        'includes' => 'include',
        'serializer' => 'League\\Fractal\\Serializer\\DataArraySerializer',
    ],
    'dompdf' => [
        'show_warnings' => false,
        'public_path' => null,
        'convert_entities' => true,
        'options' => [
            'font_dir' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts',
            'font_cache' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\fonts',
            'temp_dir' => 'C:\\Users\\sande\\AppData\\Local\\Temp',
            'chroot' => 'C:\\wamp64\\www\\agora-invoicing-community',
            'allowed_protocols' => [
                'file://' => [
                    'rules' => [
                    ],
                ],
                'http://' => [
                    'rules' => [
                    ],
                ],
                'https://' => [
                    'rules' => [
                    ],
                ],
            ],
            'log_output_file' => null,
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
        ],
        'orientation' => 'portrait',
        'defines' => [
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
        ],
    ],
    'excel' => [
        'exports' => [
            'chunk_size' => 1000,
            'pre_calculate_formulas' => false,
            'strict_null_comparison' => false,
            'csv' => [
                'delimiter' => ',',
                'enclosure' => '"',
                'line_ending' => '
',
                'use_bom' => false,
                'include_separator_line' => false,
                'excel_compatibility' => false,
                'output_encoding' => '',
            ],
            'properties' => [
                'creator' => '',
                'lastModifiedBy' => '',
                'title' => '',
                'description' => '',
                'subject' => '',
                'keywords' => '',
                'category' => '',
                'manager' => '',
                'company' => '',
            ],
        ],
        'imports' => [
            'read_only' => true,
            'ignore_empty' => false,
            'heading_row' => [
                'formatter' => 'slug',
            ],
            'csv' => [
                'delimiter' => null,
                'enclosure' => '"',
                'escape_character' => '\\',
                'contiguous' => false,
                'input_encoding' => 'UTF-8',
            ],
            'properties' => [
                'creator' => '',
                'lastModifiedBy' => '',
                'title' => '',
                'description' => '',
                'subject' => '',
                'keywords' => '',
                'category' => '',
                'manager' => '',
                'company' => '',
            ],
        ],
        'extension_detector' => [
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
        ],
        'value_binder' => [
            'default' => 'Maatwebsite\\Excel\\DefaultValueBinder',
        ],
        'cache' => [
            'enable' => true,
            'driver' => 'memory',
            'settings' => [
                'memoryCacheSize' => '32MB',
                'cacheTime' => 600,
            ],
            'memcache' => [
                'host' => 'localhost',
                'port' => 11211,
            ],
            'dir' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\cache',
        ],
        'transactions' => [
            'handler' => 'db',
            'db' => [
                'connection' => null,
            ],
        ],
        'temporary_files' => [
            'local_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/cache/laravel-excel',
            'remote_disk' => null,
            'remote_prefix' => null,
            'force_resync_remote' => null,
        ],
        'properties' => [
            'creator' => 'Maatwebsite',
            'lastModifiedBy' => 'Maatwebsite',
            'title' => 'Spreadsheet',
            'description' => 'Default spreadsheet export',
            'subject' => 'Spreadsheet export',
            'keywords' => 'maatwebsite, excel, export',
            'category' => 'Excel',
            'manager' => 'Maatwebsite',
            'company' => 'Maatwebsite',
        ],
        'sheets' => [
            'pageSetup' => [
                'orientation' => 'portrait',
                'paperSize' => '9',
                'scale' => '100',
                'fitToPage' => false,
                'fitToHeight' => true,
                'fitToWidth' => true,
                'columnsToRepeatAtLeft' => [
                    0 => '',
                    1 => '',
                ],
                'rowsToRepeatAtTop' => [
                    0 => 0,
                    1 => 0,
                ],
                'horizontalCentered' => false,
                'verticalCentered' => false,
                'printArea' => null,
                'firstPageNumber' => null,
            ],
        ],
        'creator' => 'Maatwebsite',
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => '
',
            'use_bom' => false,
        ],
        'export' => [
            'autosize' => true,
            'generate_heading_by_indices' => true,
            'merged_cell_alignment' => 'left',
            'calculate' => false,
            'includeCharts' => false,
            'sheets' => [
                'page_margin' => false,
                'nullValue' => null,
                'startCell' => 'A1',
                'strictNullComparison' => false,
            ],
            'store' => [
                'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\exports',
                'returnInfo' => false,
            ],
            'pdf' => [
                'driver' => 'DomPDF',
                'drivers' => [
                    'DomPDF' => [
                        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/dompdf/dompdf/',
                    ],
                    'tcPDF' => [
                        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/tecnick.com/tcpdf/',
                    ],
                    'mPDF' => [
                        'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\vendor/mpdf/mpdf/',
                    ],
                ],
            ],
        ],
        'filters' => [
            'registered' => [
                'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
            ],
            'enabled' => [
            ],
        ],
        'import' => [
            'heading' => 'slugged',
            'startRow' => 1,
            'separator' => '_',
            'slug_whitelist' => '._',
            'includeCharts' => false,
            'to_ascii' => true,
            'encoding' => [
                'input' => 'UTF-8',
                'output' => 'UTF-8',
            ],
            'calculate' => true,
            'ignoreEmpty' => false,
            'force_sheets_collection' => false,
            'dates' => [
                'enabled' => true,
                'format' => false,
                'columns' => [
                ],
            ],
            'sheets' => [
                'test' => [
                    'firstname' => 'A2',
                ],
            ],
        ],
        'views' => [
            'styles' => [
                'th' => [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ],
                'strong' => [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ],
                'b' => [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ],
                'i' => [
                    'font' => [
                        'italic' => true,
                        'size' => 12,
                    ],
                ],
                'h1' => [
                    'font' => [
                        'bold' => true,
                        'size' => 24,
                    ],
                ],
                'h2' => [
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                    ],
                ],
                'h3' => [
                    'font' => [
                        'bold' => true,
                        'size' => 13.5,
                    ],
                ],
                'h4' => [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ],
                'h5' => [
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                    ],
                ],
                'h6' => [
                    'font' => [
                        'bold' => true,
                        'size' => 7.5,
                    ],
                ],
                'a' => [
                    'font' => [
                        'underline' => true,
                        'color' => [
                            'argb' => 'FF0000FF',
                        ],
                    ],
                ],
                'hr' => [
                    'borders' => [
                        'bottom' => [
                            'style' => 'thin',
                            'color' => [
                                0 => 'FF000000',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'filesystems' => [
        'default' => 'local',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app',
                'throw' => false,
            ],
            'public' => [
                'driver' => 'local',
                'root' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/public',
                'url' => 'https://localhost/agora-invoicing-community/public/storage',
                'visibility' => 'public',
                'throw' => false,
            ],
            's3' => [
                'driver' => 's3',
                'key' => null,
                'secret' => null,
                'region' => null,
                'bucket' => null,
                'url' => null,
                'endpoint' => null,
                'use_path_style_endpoint' => false,
                'throw' => false,
            ],
        ],
        'links' => [
            'C:\\wamp64\\www\\agora-invoicing-community\\public\\storage' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/public',
        ],
    ],
    'geoip' => [
        'log_failures' => true,
        'include_currency' => true,
        'service' => 'ipapi',
        'services' => [
            'maxmind_database' => [
                'class' => 'Torann\\GeoIP\\Services\\MaxMindDatabase',
                'database_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/geoip.mmdb',
                'update_url' => 'https://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz',
                'locales' => [
                    0 => 'en',
                ],
            ],
            'maxmind_api' => [
                'class' => 'Torann\\GeoIP\\Services\\MaxMindWebService',
                'user_id' => null,
                'license_key' => null,
                'locales' => [
                    0 => 'en',
                ],
            ],
            'ipapi' => [
                'class' => 'Torann\\GeoIP\\Services\\IPApi',
                'secure' => true,
                'key' => null,
                'continent_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/continents.json',
                'lang' => 'en',
            ],
            'ipgeolocation' => [
                'class' => 'Torann\\GeoIP\\Services\\IPGeoLocation',
                'secure' => true,
                'key' => null,
                'continent_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\app/continents.json',
                'lang' => 'en',
            ],
            'ipdata' => [
                'class' => 'Torann\\GeoIP\\Services\\IPData',
                'key' => null,
                'secure' => true,
            ],
        ],
        'cache' => 'all',
        'cache_tags' => [
            0 => 'torann-geoip-location',
        ],
        'cache_expires' => 30,
        'default_location' => [
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
        ],
    ],
    'google2fa' => [
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
        'error_messages' => [
            'wrong_otp' => 'The \'One Time Password\' typed was wrong.',
            'cannot_be_empty' => 'One Time Password cannot be empty.',
            'unknown' => 'An unknown error has occurred. Please try again.',
        ],
        'throw_exceptions' => true,
        'qrcode_image_backend' => 'imagemagick',
    ],
    'gravatar' => [
        'default' => [
            'size' => 80,
            'default' => 'identicon',
            'maxRating' => 'g',
            'fallback' => 'mm',
            'secure' => false,
            'maximumRating' => 'g',
            'forceDefault' => false,
            'forceExtension' => 'jpg',
        ],
        'small-secure' => [
            'size' => 30,
            'secure' => true,
        ],
        'medium' => [
            'size' => 150,
        ],
    ],
    'hashing' => [
        'driver' => 'bcrypt',
        'bcrypt' => [
            'rounds' => 10,
        ],
        'argon' => [
            'memory' => 65536,
            'threads' => 1,
            'time' => 4,
        ],
    ],
    'horizon' => [
        'domain' => null,
        'path' => 'horizon',
        'use' => 'default',
        'prefix' => 'agora_horizon:',
        'middleware' => [
            0 => 'web',
            1 => 'auth',
            2 => 'admin',
        ],
        'waits' => [
            'redis:default' => 60,
        ],
        'trim' => [
            'recent' => 60,
            'pending' => 60,
            'completed' => 60,
            'recent_failed' => 10080,
            'failed' => 10080,
            'monitored' => 10080,
        ],
        'metrics' => [
            'trim_snapshots' => [
                'job' => 24,
                'queue' => 24,
            ],
        ],
        'fast_termination' => false,
        'memory_limit' => 64,
        'defaults' => [
            'supervisor-1' => [
                'connection' => 'redis',
                'queue' => [
                    0 => 'default',
                ],
                'balance' => 'auto',
                'maxProcesses' => 1,
                'maxTime' => 0,
                'maxJobs' => 0,
                'memory' => 128,
                'tries' => 1,
                'timeout' => 60,
                'nice' => 0,
            ],
        ],
        'environments' => [
            'production' => [
                'supervisor-1' => [
                    'connection' => 'redis',
                    'queue' => [
                        0 => 'default',
                    ],
                    'balance' => 'simple',
                    'processes' => 10,
                    'tries' => 1,
                    'nice' => 0,
                ],
            ],
            'local' => [
                'supervisor-1' => [
                    'connection' => 'redis',
                    'queue' => [
                        0 => 'default',
                    ],
                    'balance' => 'simple',
                    'processes' => 3,
                    'tries' => 1,
                    'nice' => 0,
                ],
            ],
        ],
    ],
    'installer' => [
        'core' => [
            'minPhpVersion' => '7.3.0',
        ],
        'requirements' => [
            'php' => [
                0 => 'openssl',
                1 => 'pdo',
                2 => 'mbstring',
                3 => 'tokenizer',
                4 => 'JSON',
                5 => 'cURL',
            ],
            'apache' => [
                0 => 'mod_rewrite',
            ],
        ],
        'permissions' => [
            'storage/framework/' => '775',
            'storage/logs/' => '775',
            'bootstrap/cache/' => '775',
        ],
        'environment' => [
            'form' => [
                'rules' => [
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
                ],
            ],
        ],
        'installed' => [
            'redirectOptions' => [
                'route' => [
                    'name' => 'welcome',
                    'data' => [
                    ],
                ],
                'abort' => [
                    'type' => '404',
                ],
                'dump' => [
                    'data' => 'Dumping a not found message.',
                ],
            ],
        ],
        'installedAlreadyAction' => '',
        'updaterEnabled' => 'true',
    ],
    'log-viewer' => [
        'storage-path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs',
        'pattern' => [
            'prefix' => 'laravel-',
            'date' => '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]',
            'extension' => '.log',
        ],
        'locale' => 'auto',
        'theme' => 'bootstrap-4',
        'route' => [
            'enabled' => true,
            'attributes' => [
                'prefix' => 'log-viewer',
                'middleware' => null,
            ],
        ],
        'per-page' => 30,
        'download' => [
            'prefix' => 'laravel-',
            'extension' => 'log',
        ],
        'menu' => [
            'filter-route' => 'log-viewer::logs.filter',
            'icons-enabled' => true,
        ],
        'icons' => [
            'all' => 'fa fa-fw fa-list',
            'emergency' => 'fa fa-fw fa-bug',
            'alert' => 'fa fa-fw fa-bullhorn',
            'critical' => 'fa fa-fw fa-heartbeat',
            'error' => 'fa fa-fw fa-times-circle',
            'warning' => 'fa fa-fw fa-exclamation-triangle',
            'notice' => 'fa fa-fw fa-exclamation-circle',
            'info' => 'fa fa-fw fa-info-circle',
            'debug' => 'fa fa-fw fa-life-ring',
        ],
        'colors' => [
            'levels' => [
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
            ],
        ],
        'highlight' => [
            0 => '^#\\d+',
            1 => '^Stack trace:',
        ],
        'facade' => 'LogViewer',
    ],
    'logging' => [
        'default' => 'stack',
        'deprecations' => 'null',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => [
                    0 => 'single',
                ],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
                'level' => 'debug',
            ],
            'daily' => [
                'driver' => 'daily',
                'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
                'level' => 'debug',
                'days' => 7,
            ],
            'slack' => [
                'driver' => 'slack',
                'url' => null,
                'username' => 'Laravel Log',
                'emoji' => ':boom:',
                'level' => 'critical',
            ],
            'papertrail' => [
                'driver' => 'monolog',
                'level' => 'debug',
                'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
                'handler_with' => [
                    'host' => null,
                    'port' => null,
                    'connectionString' => 'tls://:',
                ],
            ],
            'stderr' => [
                'driver' => 'monolog',
                'level' => 'debug',
                'handler' => 'Monolog\\Handler\\StreamHandler',
                'formatter' => null,
                'with' => [
                    'stream' => 'php://stderr',
                ],
            ],
            'syslog' => [
                'driver' => 'syslog',
                'level' => 'debug',
            ],
            'errorlog' => [
                'driver' => 'errorlog',
                'level' => 'debug',
            ],
            'null' => [
                'driver' => 'monolog',
                'handler' => 'Monolog\\Handler\\NullHandler',
            ],
            'emergency' => [
                'path' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\logs/laravel.log',
            ],
        ],
    ],
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'default' => 'smtp',
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => 'smtp.mailtrap.io',
                'port' => '2525',
                'encryption' => null,
                'username' => null,
                'password' => null,
                'timeout' => null,
                'local_domain' => null,
            ],
            'ses' => [
                'transport' => 'ses',
            ],
            'mailgun' => [
                'transport' => 'mailgun',
            ],
            'postmark' => [
                'transport' => 'postmark',
            ],
            'sendmail' => [
                'transport' => 'sendmail',
                'path' => '/usr/sbin/sendmail -bs -i',
            ],
            'log' => [
                'transport' => 'log',
                'channel' => null,
            ],
            'array' => [
                'transport' => 'array',
            ],
            'failover' => [
                'transport' => 'failover',
                'mailers' => [
                    0 => 'smtp',
                    1 => 'log',
                ],
            ],
        ],
        'from' => [
            'address' => 'hello@example.com',
            'name' => 'Example',
        ],
        'encryption' => '',
        'username' => '',
        'password' => '',
        'sendmail' => '/usr/sbin/sendmail -bs',
        'pretend' => true,
        'markdown' => [
            'theme' => 'default',
            'paths' => [
                0 => 'C:\\wamp64\\www\\agora-invoicing-community\\resources\\views/vendor/mail',
            ],
        ],
    ],
    'mailchimp' => [
        'apikey' => '',
    ],
    'markdown' => [
        'views' => true,
        'extensions' => [
        ],
        'renderer' => [
            'block_separator' => '
',
            'inner_separator' => '
',
            'soft_break' => '
',
        ],
        'commonmark' => [
            'enable_em' => true,
            'enable_strong' => true,
            'use_asterisk' => true,
            'use_underscore' => true,
            'unordered_list_markers' => [
                0 => '-',
                1 => '+',
                2 => '*',
            ],
        ],
        'html_input' => 'strip',
        'allow_unsafe_links' => true,
        'max_nesting_level' => 9223372036854775807,
        'slug_normalizer' => [
            'max_length' => 255,
            'unique' => 'document',
        ],
        'enable_em' => true,
        'enable_strong' => true,
        'use_asterisk' => true,
        'use_underscore' => true,
    ],
    'queue' => [
        'default' => 'sync',
        'connections' => [
            'sync' => [
                'driver' => 'sync',
            ],
            'database' => [
                'driver' => 'database',
                'table' => 'jobs',
                'queue' => 'default',
                'retry_after' => 90,
                'after_commit' => false,
            ],
            'beanstalkd' => [
                'driver' => 'beanstalkd',
                'host' => 'localhost',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => 0,
                'after_commit' => false,
            ],
            'sqs' => [
                'driver' => 'sqs',
                'key' => null,
                'secret' => null,
                'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
                'queue' => 'default',
                'suffix' => null,
                'region' => 'us-east-1',
                'after_commit' => false,
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'default',
                'retry_after' => 90,
                'block_for' => null,
                'after_commit' => false,
            ],
        ],
        'failed' => [
            'driver' => 'database-uuids',
            'database' => 'mysql',
            'table' => 'failed_jobs',
        ],
    ],
    'referer' => [
        'session_key' => 'referer',
        'sources' => [
            0 => 'Spatie\\Referer\\Sources\\UtmSource',
            1 => 'Spatie\\Referer\\Sources\\RequestHeader',
        ],
    ],
    'services' => [
        'mailgun' => [
            'domain' => null,
            'secret' => null,
            'endpoint' => 'api.mailgun.net',
            'scheme' => 'https',
        ],
        'mandrill' => [
            'secret' => '',
        ],
        'postmark' => [
            'token' => null,
        ],
        'ses' => [
            'key' => null,
            'secret' => null,
            'region' => 'us-east-1',
        ],
        'sparkpost' => [
            'secret' => null,
        ],
        'stripe' => [
            'model' => 'User',
            'secret' => '',
        ],
    ],
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => false,
        'files' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework/sessions',
        'connection' => null,
        'table' => 'sessions',
        'store' => null,
        'lottery' => [
            0 => 2,
            1 => 100,
        ],
        'cookie' => 'agora_session',
        'path' => '/',
        'domain' => null,
        'secure' => null,
        'http_only' => true,
        'same_site' => 'lax',
    ],
    'shopping_cart' => [
        'format_numbers' => false,
        'decimals' => 0,
        'dec_point' => '.',
        'thousands_sep' => ',',
        'storage' => null,
        'events' => null,
    ],
    'tracker' => [
        'enabled' => true,
        'cache_enabled' => true,
        'use_middleware' => true,
        'do_not_track_robots' => false,
        'do_not_track_environments' => [
        ],
        'do_not_track_routes' => [
            0 => 'tracker.stats.*',
        ],
        'do_not_track_paths' => [
            0 => 'api/*',
        ],
        'do_not_track_ips' => [
            0 => '127.0.0.0/24',
        ],
        'log_untrackable_sessions' => true,
        'log_enabled' => false,
        'console_log_enabled' => false,
        'log_sql_queries' => false,
        'connection' => 'tracker',
        'do_not_log_sql_queries_connections' => [
            0 => 'tracker',
        ],
        'geoip_database_path' => 'C:\\wamp64\\www\\agora-invoicing-community\\config/geoip',
        'log_sql_queries_bindings' => false,
        'log_events' => false,
        'log_only_events' => [
        ],
        'id_columns_names' => [
            0 => 'id',
        ],
        'do_not_log_events' => [
            0 => 'illuminate.log',
            1 => 'eloquent.*',
            2 => 'router.*',
            3 => 'composing: *',
            4 => 'creating: *',
        ],
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
        'authentication_ioc_binding' => [
            0 => 'auth',
        ],
        'authentication_guards' => [
        ],
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
    ],
    'transform' => [
        'cart' => [
            'group' => '{{group}}',
            'name' => '{{name}}',
            'price' => '{{price}}',
            'price-description' => '{{price-description}}',
            'feature' => '<li>{{feature}}</li>',
            'subscription' => '{{subscription}}',
            'url' => '{{url}}',
        ],
        'welcome_mail' => [
            'website_url' => '{{website_url}}',
            'name' => '{{name}}',
            'username' => '{{username}}',
            'password' => '{{password}}',
            'url' => '{{url}}',
        ],
        'order_mail' => [
            'name' => '{{name}}',
            'downloadurl' => '{{downloadurl}}',
            'invoiceurl' => '{{invoiceurl}}',
            'product' => '{{product}}',
            'number' => '{{number}}',
            'expiry' => '{{expiry}}',
            'url' => '{{url}}',
            'serialkeyurl' => '{{serialkeyurl}}',
            'knowledge_base' => '{{knowledge_base}}',
        ],
        'invoice_mail' => [
            'name' => '{{name}}',
            'number' => '{{number}}',
            'address' => '{{address}}',
            'invoiceurl' => '{{invoiceurl}}',
            'total' => '{{total}}',
            'content' => '{{content}}',
            'currency' => '{{currency}}',
        ],
        'forgot_password_mail' => [
            'name' => '{{name}}',
            'url' => '{{url}}',
            'contact-us' => '{{contact-us}}',
        ],
        'subscription_going_to_end_mail' => [
            'name' => '{{name}}',
            'number' => '{{number}}',
            'product' => '{{product}}',
            'expiry' => '{{expiry}}',
            'url' => '{{url}}',
        ],
        'subscription_over_mail' => [
            'name' => '{{name}}',
            'number' => '{{number}}',
            'product' => '{{product}}',
            'expiry' => '{{expiry}}',
            'url' => '{{url}}',
        ],
        'sales_manager_email' => [
            'name' => '{{name}}',
            'manager_first_name' => '{{manager_first_name}}',
            'manager_last_name' => '{{manager_last_name}}',
            'manager_email' => '{{manager_email}}',
            'manager_code' => '{{manager_code}}',
            'manager_mobile' => '{{manager_mobile}}',
            'manager_skype' => '{{manager_skype}}',
        ],
        'account_manager_email' => [
            'name' => '{{name}}',
            'manager_first_name' => '{{manager_first_name}}',
            'manager_last_name' => '{{manager_last_name}}',
            'manager_email' => '{{manager_email}}',
            'manager_code' => '{{manager_code}}',
            'manager_mobile' => '{{manager_mobile}}',
            'manager_skype' => '{{manager_skype}}',
        ],
        'password_mail' => [
            'name' => '{{name}}',
            'username' => '{{username}}',
            'password' => '{{password}}',
        ],
    ],
    'view' => [
        'paths' => [
            0 => 'C:\\wamp64\\www\\agora-invoicing-community\\resources\\views',
        ],
        'compiled' => 'C:\\wamp64\\www\\agora-invoicing-community\\storage\\framework\\views',
    ],
    'visitortracker' => [
        'dont_record' => [
        ],
        'dont_record_geoip' => [
        ],
        'dont_track_authenticated_users' => true,
        'dont_track_anonymous_users' => false,
        'dont_track_users' => [
        ],
        'login_attempt' => [
            'url' => '/login',
            'method' => 'POST',
            'is_ajax' => false,
        ],
        'geoip_on' => false,
        'geoip_driver' => 'ipstack.com',
        'ipstack_key' => '',
        'users_table' => 'users',
        'layout' => 'themes.default1.common.stats',
        'section_content' => 'content',
        'results_per_page' => 15,
        'datetime_format' => 'd M Y, H:i:s',
        'timezone' => 'Asia/Kolkata',
    ],
    'flare' => [
        'key' => null,
        'flare_middleware' => [
            0 => 'Spatie\\FlareClient\\FlareMiddleware\\RemoveRequestIp',
            1 => 'Spatie\\FlareClient\\FlareMiddleware\\AddGitInformation',
            2 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddNotifierName',
            3 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddEnvironmentInformation',
            4 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddExceptionInformation',
            5 => 'Spatie\\LaravelIgnition\\FlareMiddleware\\AddDumps',
            'Spatie\\LaravelIgnition\\FlareMiddleware\\AddLogs' => [
                'maximum_number_of_collected_logs' => 200,
            ],
            'Spatie\\LaravelIgnition\\FlareMiddleware\\AddQueries' => [
                'maximum_number_of_collected_queries' => 200,
                'report_query_bindings' => true,
            ],
            'Spatie\\LaravelIgnition\\FlareMiddleware\\AddJobs' => [
                'max_chained_job_reporting_depth' => 5,
            ],
            'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestBodyFields' => [
                'censor_fields' => [
                    0 => 'password',
                    1 => 'password_confirmation',
                ],
            ],
            'Spatie\\FlareClient\\FlareMiddleware\\CensorRequestHeaders' => [
                'headers' => [
                    0 => 'API-KEY',
                ],
            ],
        ],
        'send_logs_as_events' => true,
    ],
    'ignition' => [
        'editor' => 'phpstorm',
        'theme' => 'auto',
        'enable_share_button' => true,
        'register_commands' => false,
        'solution_providers' => [
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
        ],
        'ignored_solution_providers' => [
        ],
        'enable_runnable_solutions' => false,
        'remote_sites_path' => 'C:\\wamp64\\www\\agora-invoicing-community',
        'local_sites_path' => '',
        'housekeeping_endpoint_prefix' => '_ignition',
        'settings_file_path' => '',
    ],
    'datatables-html' => [
        'namespace' => 'LaravelDataTables',
        'table' => [
            'class' => 'table',
            'id' => 'dataTableBuilder',
        ],
        'callback' => [
            0 => '$',
            1 => '$.',
            2 => 'function',
        ],
        'script' => 'datatables::script',
        'editor' => 'datatables::editor',
    ],
    'tinker' => [
        'commands' => [
        ],
        'alias' => [
        ],
        'dont_alias' => [
            0 => 'App\\Nova',
        ],
    ],
];
