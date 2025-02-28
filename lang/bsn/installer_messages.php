<?php

return [

    'title' => 'Agora Invoicing Instalater',
    'probe' => 'Agora Invoicing Provjere',
    'magic_phrase' => 'Koja je magična fraza',
    'server_requirements' => 'Zahtjevi servera',
    'database_setup' => 'Postavljanje baze podataka',
    'getting_started' => 'Početak',
    'final' => 'Završno',
    'directory' => 'Direktorij',
    'permissions' => 'Dozvole',
    'requisites' => 'Zahtjevi',
    'status' => 'Status',
    'php_extensions' => 'PHP Ekstenzije',
    'not_enabled' => 'Nije omogućeno',
    'extension_not_enabled' => 'Nije omogućeno: Da biste omogućili ovo, instalirajte ekstenziju na svom serveru i ažurirajte :php_ini_file da omogućite :extensionName. <a href=":url" target="_blank">Kako instalirati PHP ekstenzije na mom serveru?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'ISKLJUČENO (Ako koristite Apache, provjerite da li je <var><strong>AllowOverride</strong></var> postavljeno na <var><strong>All</strong></var> u Apache konfiguraciji)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'Korisnički prijateljski URL',

    'host' => 'Host',
    'host_tooltip' => 'Ako je vaš MySQL instaliran na istom serveru kao Agora Invoicing, neka ostane localhost',
    'database_name_label' => 'Naziv baze podataka',
    'mysql_port_label' => 'MySQL port broj',
    'mysql_port_tooltip' => 'Broj porta na kojem vaš MySQL server sluša. Podrazumijevano je 3306',
    'username' => 'Korisničko ime',
    'password_label' => 'Lozinka',
    'test_prerequisites_message' => 'Ovaj test će provjeriti potrebne uslove za instalaciju Agora Invoicing',
    'previous' => 'Prethodno',

    'sign_up_as_admin' => 'Registrujte se kao administrator',
    'first_name' => 'Ime',
    'first_name_required' => 'Ime je obavezno',
    'last_name' => 'Prezime',
    'last_name_required' => 'Prezime je obavezno',
    'username_info' => 'Korisničko ime može sadržavati samo alfanumeričke znakove, razmake, donje crte, crtice, tačke i simbol @.',
    'email' => 'Email',
    'email_required' => 'Email korisnika je obavezan',
    'password_required' => 'Lozinka je obavezna',
    'confirm_password' => 'Potvrdi lozinku',
    'confirm_password_required' => 'Potvrda lozinke je obavezna',
    'password_requirements' => 'Vaša lozinka mora sadržavati:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Između 8-16 karaktera'],
        ['id' => 'letter', 'text' => 'Mala slova (a-z)'],
        ['id' => 'capital', 'text' => 'Velika slova (A-Z)'],
        ['id' => 'number', 'text' => 'Brojevi (0-9)'],
        ['id' => 'space', 'text' => 'Specijalni znakovi (~*!@$#%_+.?:,{ })'],
    ],

    // Informacije o sistemu
    'system_information' => 'Informacije o sistemu',
    'environment' => 'Okruženje',
    'environment_required' => 'Okruženje je obavezno',
    'production' => 'Produkcija',
    'development' => 'Razvoj',
    'testing' => 'Testiranje',
    'cache_driver' => 'Keš drajver',
    'cache_driver_required' => 'Keš drajver je obavezan',
    'file' => 'Fajl',
    'redis' => 'Redis',
    'password' => 'Lozinka',

    // Redis podešavanje
    'redis_setup' => 'Redis podešavanje',
    'redis_host' => 'Redis Host',
    'redis_port' => 'Redis Port',
    'redis_password' => 'Redis Lozinka',

    // Dugmad
    'continue' => 'Nastavi',

    // Završno podešavanje
    'final_setup' => 'Vaša Agora Invoicing aplikacija je spremna!',
    'installation_complete' => 'Čestitamo! Uspješno ste završili instalaciju.',

    // Naučite više
    'learn_more' => 'Saznajte više',
    'knowledge_base' => 'Baza znanja',
    'email_support' => 'Email podrška',

    // Sljedeći korak
    'next_step' => 'Sljedeći korak',
    'login_button' => 'Prijavite se u Agora Invoicing',

    'pre_migration_success' => 'Pre-migracija je uspješno testirana',
    'migrating_tables' => 'Migracija tabela u bazi podataka',
    'db_connection_error' => 'Veza s bazom podataka nije ažurirana.',
    'database_setup_success' => 'Baza podataka je uspješno postavljena.',
    'env_file_created' => 'Konfiguracijski fajl okruženja je uspješno kreiran',
    'pre_migration_test' => 'Pokretanje pre-migracijskog testa',

    'redis_host_required' => 'Redis host je obavezan.',
    'redis_password_required' => 'Redis lozinka je obavezna.',
    'redis_port_required' => 'Redis port je obavezan.',
    'password_regex' => 'Lozinka mora sadržavati najmanje 8 karaktera, jedno veliko slovo, jedno malo slovo, jedan broj i jedan specijalni znak.',
    'setup_completed' => 'Postavljanje je uspješno završeno!',

    'database' => 'Baza podataka',
    'selected' => 'Odabrano',
    'mysql_version_is' => 'MySQL verzija je',
    'database_empty' => 'Baza podataka je prazna',
    'database_not_empty' => 'Agora Invoicing instalacija zahtijeva praznu bazu podataka, ali vaša baza već sadrži tabele i podatke.',
    'mysql_version_required' => 'Preporučujemo nadogradnju na najmanje MySQL 5.6 ili MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Veza s bazom podataka nije uspjela.',
    'connected_as' => 'Povezan s bazom podataka kao',
    'failed_connection' => 'Neuspješno povezivanje s bazom podataka.',
    'magic_phrase_not_work' => 'Unesena magična fraza ne radi.',
    'magic_required' => 'Magična fraza je obavezna.',
    'user_name_regex' => 'Korisničko ime mora imati između 3 i 20 karaktera i može sadržavati samo slova, brojeve, razmake, donje crte, crtice, tačke i simbol @.',

];
