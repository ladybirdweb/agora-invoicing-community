<?php return array (
  'providers' => 
  array (
    0 => 'App\\Plugins\\Ccavanue\\ServiceProvider',
    1 => 'App\\Plugins\\Ping\\ServiceProvider',
    2 => 'App\\Plugins\\Twilio\\ServiceProvider',
    3 => 'Illuminate\\Auth\\AuthServiceProvider',
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
    14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
    15 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
    16 => 'Illuminate\\Queue\\QueueServiceProvider',
    17 => 'Illuminate\\Redis\\RedisServiceProvider',
    18 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    19 => 'Illuminate\\Session\\SessionServiceProvider',
    20 => 'Illuminate\\Translation\\TranslationServiceProvider',
    21 => 'Illuminate\\Validation\\ValidationServiceProvider',
    22 => 'Illuminate\\View\\ViewServiceProvider',
    23 => 'Illuminate\\Html\\HtmlServiceProvider',
    24 => 'Thomaswelton\\LaravelGravatar\\LaravelGravatarServiceProvider',
    25 => 'Chumper\\Datatable\\DatatableServiceProvider',
    26 => 'Collective\\Html\\HtmlServiceProvider',
    27 => 'Darryldecode\\Cart\\CartServiceProvider',
    28 => 'App\\Providers\\AppServiceProvider',
    29 => 'App\\Providers\\EventServiceProvider',
    30 => 'App\\Providers\\RouteServiceProvider',
    31 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
  ),
  'eager' => 
  array (
    0 => 'App\\Plugins\\Ccavanue\\ServiceProvider',
    1 => 'App\\Plugins\\Ping\\ServiceProvider',
    2 => 'App\\Plugins\\Twilio\\ServiceProvider',
    3 => 'Illuminate\\Auth\\AuthServiceProvider',
    4 => 'Illuminate\\Cookie\\CookieServiceProvider',
    5 => 'Illuminate\\Database\\DatabaseServiceProvider',
    6 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
    7 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
    8 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
    9 => 'Illuminate\\Pagination\\PaginationServiceProvider',
    10 => 'Illuminate\\Session\\SessionServiceProvider',
    11 => 'Illuminate\\View\\ViewServiceProvider',
    12 => 'Thomaswelton\\LaravelGravatar\\LaravelGravatarServiceProvider',
    13 => 'Chumper\\Datatable\\DatatableServiceProvider',
    14 => 'Darryldecode\\Cart\\CartServiceProvider',
    15 => 'App\\Providers\\AppServiceProvider',
    16 => 'App\\Providers\\EventServiceProvider',
    17 => 'App\\Providers\\RouteServiceProvider',
  ),
  'deferred' => 
  array (
    'Illuminate\\Bus\\Dispatcher' => 'Illuminate\\Bus\\BusServiceProvider',
    'Illuminate\\Contracts\\Bus\\Dispatcher' => 'Illuminate\\Bus\\BusServiceProvider',
    'Illuminate\\Contracts\\Bus\\QueueingDispatcher' => 'Illuminate\\Bus\\BusServiceProvider',
    'cache' => 'Illuminate\\Cache\\CacheServiceProvider',
    'cache.store' => 'Illuminate\\Cache\\CacheServiceProvider',
    'memcached.connector' => 'Illuminate\\Cache\\CacheServiceProvider',
    'command.cache.clear' => 'Illuminate\\Cache\\CacheServiceProvider',
    'command.clear-compiled' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.auth.resets.clear' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.config.cache' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.config.clear' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.down' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.environment' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.key.generate' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.optimize' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.cache' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.clear' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.route.list' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.tinker' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.up' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.view.clear' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'Illuminate\\Console\\Scheduling\\ScheduleRunCommand' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migrator' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migration.repository' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.rollback' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.reset' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.refresh' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.install' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.status' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'migration.creator' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.migrate.make' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'seeder' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.seed' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'composer' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.failed' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.retry' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.forget' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'command.queue.flush' => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
    'hash' => 'Illuminate\\Hashing\\HashServiceProvider',
    'mailer' => 'Illuminate\\Mail\\MailServiceProvider',
    'swift.mailer' => 'Illuminate\\Mail\\MailServiceProvider',
    'swift.transport' => 'Illuminate\\Mail\\MailServiceProvider',
    'Illuminate\\Contracts\\Pipeline\\Hub' => 'Illuminate\\Pipeline\\PipelineServiceProvider',
    'queue' => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.worker' => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.listener' => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.failer' => 'Illuminate\\Queue\\QueueServiceProvider',
    'command.queue.work' => 'Illuminate\\Queue\\QueueServiceProvider',
    'command.queue.listen' => 'Illuminate\\Queue\\QueueServiceProvider',
    'command.queue.restart' => 'Illuminate\\Queue\\QueueServiceProvider',
    'queue.connection' => 'Illuminate\\Queue\\QueueServiceProvider',
    'redis' => 'Illuminate\\Redis\\RedisServiceProvider',
    'auth.password' => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    'auth.password.broker' => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
    'translator' => 'Illuminate\\Translation\\TranslationServiceProvider',
    'translation.loader' => 'Illuminate\\Translation\\TranslationServiceProvider',
    'validator' => 'Illuminate\\Validation\\ValidationServiceProvider',
    'validation.presence' => 'Illuminate\\Validation\\ValidationServiceProvider',
    'html' => 'Collective\\Html\\HtmlServiceProvider',
    'form' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\HtmlBuilder' => 'Collective\\Html\\HtmlServiceProvider',
    'Collective\\Html\\FormBuilder' => 'Collective\\Html\\HtmlServiceProvider',
    'Illuminate\\Broadcasting\\BroadcastManager' => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
    'Illuminate\\Contracts\\Broadcasting\\Factory' => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
    'Illuminate\\Contracts\\Broadcasting\\Broadcaster' => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
  ),
  'when' => 
  array (
    'Illuminate\\Bus\\BusServiceProvider' => 
    array (
    ),
    'Illuminate\\Cache\\CacheServiceProvider' => 
    array (
    ),
    'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider' => 
    array (
    ),
    'Illuminate\\Hashing\\HashServiceProvider' => 
    array (
    ),
    'Illuminate\\Mail\\MailServiceProvider' => 
    array (
    ),
    'Illuminate\\Pipeline\\PipelineServiceProvider' => 
    array (
    ),
    'Illuminate\\Queue\\QueueServiceProvider' => 
    array (
    ),
    'Illuminate\\Redis\\RedisServiceProvider' => 
    array (
    ),
    'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider' => 
    array (
    ),
    'Illuminate\\Translation\\TranslationServiceProvider' => 
    array (
    ),
    'Illuminate\\Validation\\ValidationServiceProvider' => 
    array (
    ),
    'Illuminate\\Html\\HtmlServiceProvider' => 
    array (
    ),
    'Collective\\Html\\HtmlServiceProvider' => 
    array (
    ),
    'Illuminate\\Broadcasting\\BroadcastServiceProvider' => 
    array (
    ),
  ),
);