<?php

return [

    'title' => 'Agora Invoicing Installer',
    'probe' => 'Agora Invoicing Probes',
    'magic_phrase' => 'X’inhu l-frażi tal-maġija',
    'server_requirements' => 'Rekwiżiti tas-Server',
    'database_setup' => 'Impostazzjoni tad-Database',
    'getting_started' => 'Bidu',
    'final' => 'Finali',
    'directory' => 'Direttorju',
    'permissions' => 'Permessi',
    'requisites' => 'Rekwiżiti',
    'status' => 'Status',
    'php_extensions' => 'Estensjonijiet PHP',
    'not_enabled' => 'Mhux Attivat',
    'extension_not_enabled' => 'Mhux Attivat: Biex attiva dan, jekk jogħġbok installa l-estensjoni fuq is-server tiegħek u aġġorna :php_ini_file biex tgħaqqad :extensionName. <a href=":url" target="_blank">Kif tinstallaw estensjonijiet PHP fuq is-server tiegħi?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'OFF (Jekk qed tuża apache, kun żgur li <var><strong>AllowOverride</strong></var> huwa settat għal <var><strong>All</strong></var> fil-konfigurazzjoni ta\' apache)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'URL faċli għall-utent',

    'host' => 'Host',
    'host_tooltip' => 'Jekk il-MySQL tiegħek huwa installat fuq l-istess server bħal Agora Invoicing, ħalliha localhost',
    'database_name_label' => 'Isem tad-database',
    'mysql_port_label' => 'Numru tal-port MySQL',
    'mysql_port_tooltip' => 'In-numru tal-port li fuqu l-server MySQL tiegħek qed jisma’. Biex default, huwa 3306',
    'username' => 'Isem tal-Utent',
    'password_label' => 'Password',
    'test_prerequisites_message' => 'Dan it-test se jivverifika r-rekwiżiti meħtieġa biex tinstalla Agora Invoicing',
    'previous' => 'Preċedenti',

    'sign_up_as_admin' => 'Irreġistra bħala Administrator',
    'first_name' => 'Isem',
    'first_name_required' => 'Isem huwa meħtieġ',
    'last_name' => 'Kunjom',
    'last_name_required' => 'Kunjoom huwa meħtieġ',
    'username_info' => 'L-isem tal-utent jista’ jinkludi biss karattri alfanumeriċi, spazji, underscore, dash, periodi, u s-simbolu @.',
    'email' => 'Email',
    'email_required' => 'L-email tal-utent hija meħtieġa',
    'password_required' => 'Password hija meħtieġa',
    'confirm_password' => 'Ikkonferma l-password',
    'confirm_password_required' => 'Ikkonferma l-password hija meħtieġa',
    'password_requirements' => 'Il-password tiegħek trid tkun:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Bejn 8-16 karattri'],
        ['id' => 'letter', 'text' => 'Karattri żgħar (a-z)'],
        ['id' => 'capital', 'text' => 'Karattri kbar (A-Z)'],
        ['id' => 'number', 'text' => 'Numri (0-9)'],
        ['id' => 'space', 'text' => 'Karattri speċjali (~*!@$#%_+.?:,{ })'],
    ],

    // Informazzjoni tas-Systems
    'system_information' => 'Informazzjoni tas-System',
    'environment' => 'Ambjent',
    'environment_required' => 'Ambjent huwa meħtieġ',
    'production' => 'Produzzjoni',
    'development' => 'Żvilupp',
    'testing' => 'Testar',
    'cache_driver' => 'Cache Driver',
    'cache_driver_required' => 'Cache Driver huwa meħtieġ',
    'file' => 'Fajl',
    'redis' => 'Redis',
    'password' => 'Password',

    // Setup Redis
    'redis_setup' => 'Impostazzjoni Redis',
    'redis_host' => 'Host Redis',
    'redis_port' => 'Port Redis',
    'redis_password' => 'Password Redis',

    // Buttuni
    'continue' => 'Kompli',

    // Setup Finali
    'final_setup' => 'Il-applikazzjoni Agora Invoicing hija lesta!',
    'installation_complete' => 'Tajjeb, sparky! Int għaddejt mill-installazzjoni.',

    // Ikkonsidra aktar
    'learn_more' => 'Ikkonsidra aktar',
    'knowledge_base' => 'Bażi ta\' l-għarfien',
    'email_support' => 'Appoġġ email',

    // Pass li jmiss
    'next_step' => 'Pass li jmiss',
    'login_button' => 'Idħol fil-Agora Invoicing',

    'pre_migration_success' => 'Il-pre migrazzjoni ġiet ittestjata b’suċċess',
    'migrating_tables' => 'Qed tinbidel it-tabella fid-database',
    'db_connection_error' => 'Il-konnessjoni tad-database ma ġietx aġġornata.',
    'database_setup_success' => 'Il-database ġiet stabbilita b’suċċess.',
    'env_file_created' => 'Il-fajl tal-konfigurazzjoni tal-ambjent ġie maħluq b’suċċess',
    'pre_migration_test' => 'Qed nagħmlu t-test tal-pre-migrazzjoni',

    'redis_host_required' => 'Host Redis huwa meħtieġ.',
    'redis_password_required' => 'Password Redis hija meħtieġa.',
    'redis_port_required' => 'Port Redis huwa meħtieġ.',
    'password_regex' => 'Il-password trid ikollha mill-inqas 8 karattri, waħda lettera kbar, waħda żgħar, numru wieħed u karattru speċjali.',
    'setup_completed' => 'L-impostazzjoni tlestiet b’suċċess!',

    'database' => 'Database',
    'selected' => 'Magħżul',
    'mysql_version_is' => 'Il-verżjoni MySQL hija',
    'database_empty' => 'Il-database hija vojt',
    'database_not_empty' => 'L-installazzjoni ta\' Agora Invoicing tirrikjedi database vojt, il-database tiegħek diġà għandha tabelli u data fiha.',
    'mysql_version_required' => 'Nirrakkomandaw li tħares lejn aġġornamenti għal MySQL 5.6 jew MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Il-konnessjoni mal-database naqset.',
    'connected_as' => 'Konnessi bħala',
    'failed_connection' => 'Naqas li jikkonnettja mad-database.',
    'magic_phrase_not_work' => 'Il-frażi tal-maġija li daħħalt ma tkunx qed taħdem.',
    'magic_required' => 'Il-frażi tal-maġija hija meħtieġa.',
    'user_name_regex' => 'L-isem tal-utent għandu jkun bejn 3-20 karattri u jista’ jinkludi biss ittri, numri, spazji, underscores, hyphens, periodi u s-simbolu @.',

];
