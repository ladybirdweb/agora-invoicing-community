<?php

return [

    'title' => 'Agora Arveldamise Paigaldaja',
    'probe' => 'Agora Arveldamise Proovid',
    'magic_phrase' => 'Mis on maagiline fraas',
    'server_requirements' => 'Serveri Nõuded',
    'database_setup' => 'Andmebaasi Seadistamine',
    'getting_started' => 'Alustamine',
    'final' => 'Lõpp',
    'directory' => 'Kataloog',
    'permissions' => 'Luba',
    'requisites' => 'Nõuded',
    'status' => 'Oleks',
    'php_extensions' => 'PHP Laiendused',
    'not_enabled' => 'Ei ole lubatud',
    'extension_not_enabled' => 'Ei ole lubatud: Selle lubamiseks palun installige laiendus oma serverisse ja värskendage :php_ini_file, et lubada :extensionName. <a href=":url" target="_blank">Kuidas installida PHP laiendusi oma serverisse?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'VÄLJAS (Kui kasutate Apache\'it, veenduge, et <var><strong>AllowOverride</strong></var> on Apache konfiguratsioonis seatud <var><strong>All</strong></var>)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'Kasutajasõbralik URL',

    'host' => 'Host',
    'host_tooltip' => 'Kui teie MySQL on installitud samale serverile, siis las ta olla localhost',
    'database_name_label' => 'Andmebaasi nimi',
    'mysql_port_label' => 'MySQL pordi number',
    'mysql_port_tooltip' => 'Pordi number, millel teie MySQL server kuulab. Vaikimisi on see 3306',
    'username' => 'Kasutajanimi',
    'password_label' => 'Parool',
    'test_prerequisites_message' => 'See test kontrollib nõudeid, mis on vajalikud Agora Arveldamise paigaldamiseks',
    'previous' => 'Eelmine',

    'sign_up_as_admin' => 'Registreeru administraatorina',
    'first_name' => 'Eesnimi',
    'first_name_required' => 'Eesnimi on nõutav',
    'last_name' => 'Perekonnanimi',
    'last_name_required' => 'Perekonnanimi on nõutav',
    'username_info' => 'Kasutajanimi võib sisaldada ainult alfanumeerilisi märke, tühikuid, allakriipse, sidekriipse, punkte ja @ sümbolit.',
    'email' => 'E-post',
    'email_required' => 'Kasutaja e-post on nõutav',
    'password_required' => 'Parool on nõutav',
    'confirm_password' => 'Kinnita parool',
    'confirm_password_required' => 'Parooli kinnitamine on nõutav',
    'password_requirements' => 'Teie parool peab sisaldama:',
    'password_requirements_list' => [
        '8–16 tähemärki',
        'Suurtähed (A-Z)',
        'Väiketähed (a-z)',
        'Numbrid (0-9)',
        'Erimärgid (~*!@$#%_+.?:,{ })',
    ],

    // Süsteemi teave
    'system_information' => 'Süsteemi Teave',
    'environment' => 'Keskkond',
    'environment_required' => 'Keskkond on nõutav',
    'production' => 'Tootmine',
    'development' => 'Arendamine',
    'testing' => 'Testimine',
    'cache_driver' => 'Vahemälu Draiver',
    'cache_driver_required' => 'Vahemälu draiver on nõutav',
    'file' => 'Fail',
    'redis' => 'Redis',
    'password' => 'Parool',

    // Redis Seadistamine
    'redis_setup' => 'Redis Seadistamine',
    'redis_host' => 'Redis Host',
    'redis_port' => 'Redis Port',
    'redis_password' => 'Redis Parool',

    // Nupud
    'continue' => 'Jätka',

    // Lõpp Seadistus
    'final_setup' => 'Teie Agora Arveldamise Rakendus on Valmis!',
    'installation_complete' => 'Kõik on korras, sa oled läbi paigaldamise läinud.',

    // Rohkem teada
    'learn_more' => 'Uuri rohkem',
    'knowledge_base' => 'Teadmiste baas',
    'email_support' => 'E-posti Tugi',

    // Järgmine samm
    'next_step' => 'Järgmine samm',
    'login_button' => 'Logi sisse Arveldamisse',

    'pre_migration_success' => 'Eelmise migreerimise test on edukalt läbitud',
    'migrating_tables' => 'Migratsiooni tabelid andmebaasis',
    'db_connection_error' => 'Andmebaasi ühendus ei uuendatud.',
    'database_setup_success' => 'Andmebaas on edukalt seadistatud.',
    'env_file_created' => 'Keskkonna konfiguratsioonifail on edukalt loodud',
    'pre_migration_test' => 'Käivitame eelmise migreerimise testi',

    'redis_host_required' => 'Redis host on nõutav.',
    'redis_password_required' => 'Redis parool on nõutav.',
    'redis_port_required' => 'Redis port on nõutav.',
    'password_regex' => 'Parool peab sisaldama vähemalt 8 tähemärki, ühte suurtähti, ühte väiketähti, ühte numbrit ja ühte erimärki.',
    'setup_completed' => 'Seadistus on edukalt lõpetatud!',

    'database' => 'Andmebaas',
    'selected' => 'Valitud',
    'mysql_version_is' => 'MySQL versioon on',
    'database_empty' => 'Andmebaas on tühi',
    'database_not_empty' => 'Agora Arveldamise paigaldamine nõuab tühja andmebaasi, teie andmebaasis on juba tabelid ja andmed.',
    'mysql_version_required' => 'Soovitame uuendada vähemalt MySQL 5.6 või MariaDB 10.3-le!',
    'database_connection_unsuccessful' => 'Andmebaasi ühendus ebaõnnestus.',
    'connected_as' => 'Ühendatud andmebaasiga kui',
    'failed_connection' => 'Andmebaasi ühendamine ebaõnnestus.',

];
