<?php

return [

    'title' => 'Agora Invoicing Installer',
    'probe' => 'Agora Invoicing Probes',
    'magic_phrase' => 'Hva er den magiske setningen',
    'server_requirements' => 'Serverkrav',
    'database_setup' => 'Databaseoppsett',
    'getting_started' => 'Kom i gang',
    'final' => 'Slutt',
    'directory' => 'Katalog',
    'permissions' => 'Tillatelser',
    'requisites' => 'Krav',
    'status' => 'Status',
    'php_extensions' => 'PHP-utvidelser',
    'not_enabled' => 'Ikke aktivert',
    'extension_not_enabled' => 'Ikke aktivert: For å aktivere dette, vennligst installer utvidelsen på serveren og oppdater :php_ini_file for å aktivere :extensionName. <a href=":url" target="_blank">Hvordan installere PHP-utvidelser på serveren min?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'AV (Hvis du bruker apache, sørg for at <var><strong>AllowOverride</strong></var> er satt til <var><strong>All</strong></var> i apache-konfigurasjonen)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'Brukervennlig URL',

    'host' => 'Vert',
    'host_tooltip' => 'Hvis MySQL er installert på samme server som Agora Invoicing, la det være localhost',
    'database_name_label' => 'Databasenavn',
    'mysql_port_label' => 'MySQL portnummer',
    'mysql_port_tooltip' => 'Portnummeret der MySQL-serveren din lytter. Som standard er det 3306',
    'username' => 'Brukernavn',
    'password_label' => 'Passord',
    'test_prerequisites_message' => 'Denne testen vil sjekke kravene som kreves for å installere Agora Invoicing',
    'previous' => 'Forrige',

    'sign_up_as_admin' => 'Registrer deg som Admin',
    'first_name' => 'Fornavn',
    'first_name_required' => 'Fornavn er påkrevd',
    'last_name' => 'Etternavn',
    'last_name_required' => 'Etternavn er påkrevd',
    'username_info' => 'Brukernavn kan kun inneholde alfanumeriske tegn, mellomrom, understreking, bindestreker, punktum og @-symbolet.',
    'email' => 'E-post',
    'email_required' => 'Brukerens e-post er påkrevd',
    'password_required' => 'Passord er påkrevd',
    'confirm_password' => 'Bekreft passord',
    'confirm_password_required' => 'Bekreft passord er påkrevd',
    'password_requirements' => 'Passordet ditt må inneholde:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Mellom 8-16 tegn'],
        ['id' => 'letter', 'text' => 'Små bokstaver (a-z)'],
        ['id' => 'capital', 'text' => 'Store bokstaver (A-Z)'],
        ['id' => 'number', 'text' => 'Tall (0-9)'],
        ['id' => 'space', 'text' => 'Spesialtegn (~*!@$#%_+.?:,{ })'],
    ],

    // System Information
    'system_information' => 'Systeminformasjon',
    'environment' => 'Miljø',
    'environment_required' => 'Miljø er påkrevd',
    'production' => 'Produksjon',
    'development' => 'Utvikling',
    'testing' => 'Testing',
    'cache_driver' => 'Cache-driver',
    'cache_driver_required' => 'Cache-driver er påkrevd',
    'file' => 'Fil',
    'redis' => 'Redis',
    'password' => 'Passord',

    // Redis Setup
    'redis_setup' => 'Redis-oppsett',
    'redis_host' => 'Redis-vert',
    'redis_port' => 'Redis-port',
    'redis_password' => 'Redis-passord',

    // Buttons
    'continue' => 'Fortsett',

    // Final Setup
    'final_setup' => 'Agora Invoicing-applikasjonen din er klar!',
    'installation_complete' => 'Alt er klart, sparky! Du har fullført installasjonen.',

    // Learn More
    'learn_more' => 'Lær mer',
    'knowledge_base' => 'Kunnskapsbase',
    'email_support' => 'E-poststøtte',

    // Next Step
    'next_step' => 'Neste trinn',
    'login_button' => 'Logg inn på Agora Invoicing',

    'pre_migration_success' => 'Pre-migrering ble testet med suksess',
    'migrating_tables' => 'Migrerer tabeller i databasen',
    'db_connection_error' => 'Databaseforbindelsen ble ikke oppdatert.',
    'database_setup_success' => 'Databasen ble satt opp vellykket.',
    'env_file_created' => 'Konfigurasjonsfilen for miljøet ble opprettet vellykket',
    'pre_migration_test' => 'Kjører pre-migreringstest',

    'redis_host_required' => 'Redis-vert er påkrevd.',
    'redis_password_required' => 'Redis-passord er påkrevd.',
    'redis_port_required' => 'Redis-port er påkrevd.',
    'password_regex' => 'Passordet må inneholde minst 8 tegn, én stor bokstav, én liten bokstav, ett tall og ett spesialtegn.',
    'setup_completed' => 'Oppsett fullført!',

    'database' => 'Database',
    'selected' => 'Valgt',
    'mysql_version_is' => 'MySQL versjon er',
    'database_empty' => 'Databasen er tom',
    'database_not_empty' => 'Agora Invoicing-installasjonen krever en tom database, databasen din har allerede tabeller og data i seg.',
    'mysql_version_required' => 'Vi anbefaler å oppgradere til minst MySQL 5.6 eller MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Databaseforbindelse mislyktes.',
    'connected_as' => 'Koblet til databasen som',
    'failed_connection' => 'Klarte ikke å koble til databasen.',
    'magic_phrase_not_work' => 'Den magiske setningen du skrev fungerer ikke.',
    'magic_required' => 'Den magiske setningen er påkrevd.',
    'user_name_regex' => 'Brukernavn må være 3-20 tegn og kan kun inneholde bokstaver, tall, mellomrom, understreking, bindestreker, punktum og @-symbolet.',

];
