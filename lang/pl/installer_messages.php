<?php

return [

    'title' => 'Instalator Agora Invoicing',
    'probe' => 'Proby Agora Invoicing',
    'magic_phrase' => 'Jaka jest magiczna fraza',
    'server_requirements' => 'Wymagania serwerowe',
    'database_setup' => 'Konfiguracja bazy danych',
    'getting_started' => 'Pierwsze kroki',
    'final' => 'Finalny',
    'directory' => 'Katalog',
    'permissions' => 'Uprawnienia',
    'requisites' => 'Wymagania',
    'status' => 'Status',
    'php_extensions' => 'Rozszerzenia PHP',
    'not_enabled' => 'Nie włączone',
    'extension_not_enabled' => 'Nie włączone: Aby to włączyć, proszę zainstalować rozszerzenie na serwerze i zaktualizować :php_ini_file, aby włączyć :extensionName. <a href=":url" target="_blank">Jak zainstalować rozszerzenia PHP na moim serwerze?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'WYŁĄCZONE (Jeśli używasz Apache, upewnij się, że <var><strong>AllowOverride</strong></var> jest ustawione na <var><strong>All</strong></var> w konfiguracji apache)',
    'rewrite_engine' => 'Silnik przepisywania',
    'user_url' => 'Przyjazny URL',

    'host' => 'Host',
    'host_tooltip' => 'Jeśli MySQL jest zainstalowany na tym samym serwerze co Agora Invoicing, użyj localhost',
    'database_name_label' => 'Nazwa bazy danych',
    'mysql_port_label' => 'Numer portu MySQL',
    'mysql_port_tooltip' => 'Numer portu, na którym nasłuchuje Twój serwer MySQL. Domyślnie jest to 3306',
    'username' => 'Nazwa użytkownika',
    'password_label' => 'Hasło',
    'test_prerequisites_message' => 'Ten test sprawdzi wymagania wstępne potrzebne do zainstalowania Agora Invoicing',
    'previous' => 'Poprzedni',

    'sign_up_as_admin' => 'Zarejestruj się jako Administrator',
    'first_name' => 'Imię',
    'first_name_required' => 'Imię jest wymagane',
    'last_name' => 'Nazwisko',
    'last_name_required' => 'Nazwisko jest wymagane',
    'username_info' => 'Nazwa użytkownika może zawierać tylko znaki alfanumeryczne, spacje, podkreślenia, myślniki, kropki i symbol @.',
    'email' => 'Email',
    'email_required' => 'Email użytkownika jest wymagany',
    'password_required' => 'Hasło jest wymagane',
    'confirm_password' => 'Potwierdź hasło',
    'confirm_password_required' => 'Potwierdzenie hasła jest wymagane',
    'password_requirements' => 'Twoje hasło musi zawierać:',
    'password_requirements_list' => [
        'Od 8 do 16 znaków',
        'Duże litery (A-Z)',
        'Małe litery (a-z)',
        'Cyfry (0-9)',
        'Znaki specjalne (~*!@$#%_+.?:,{ })',
    ],

    // Informacje systemowe
    'system_information' => 'Informacje systemowe',
    'environment' => 'Środowisko',
    'environment_required' => 'Środowisko jest wymagane',
    'production' => 'Produkcja',
    'development' => 'Rozwój',
    'testing' => 'Testowanie',
    'cache_driver' => 'Sterownik pamięci podręcznej',
    'cache_driver_required' => 'Sterownik pamięci podręcznej jest wymagany',
    'file' => 'Plik',
    'redis' => 'Redis',
    'password' => 'Hasło',

    // Konfiguracja Redis
    'redis_setup' => 'Konfiguracja Redis',
    'redis_host' => 'Host Redis',
    'redis_port' => 'Port Redis',
    'redis_password' => 'Hasło Redis',

    // Przyciski
    'continue' => 'Kontynuuj',

    // Finalna konfiguracja
    'final_setup' => 'Twoja aplikacja Agora Invoicing jest gotowa!',
    'installation_complete' => 'Brawo, udało Ci się przejść przez instalację.',

    // Dowiedz się więcej
    'learn_more' => 'Dowiedz się więcej',
    'knowledge_base' => 'Baza wiedzy',
    'email_support' => 'Wsparcie emailowe',

    // Następny krok
    'next_step' => 'Następny krok',
    'login_button' => 'Zaloguj się do rozliczeń',

    'pre_migration_success' => 'Test migracji wstępnej zakończony pomyślnie',
    'migrating_tables' => 'Migracja tabel w bazie danych',
    'db_connection_error' => 'Połączenie z bazą danych nie zostało zaktualizowane.',
    'database_setup_success' => 'Baza danych została pomyślnie skonfigurowana.',
    'env_file_created' => 'Plik konfiguracyjny środowiska został pomyślnie utworzony',
    'pre_migration_test' => 'Przeprowadzanie testu migracji wstępnej',

    'redis_host_required' => 'Host Redis jest wymagany.',
    'redis_password_required' => 'Hasło Redis jest wymagane.',
    'redis_port_required' => 'Port Redis jest wymagany.',
    'password_regex' => 'Hasło musi zawierać co najmniej 8 znaków, jedną dużą literę, jedną małą literę, jedną cyfrę i jeden znak specjalny.',
    'setup_completed' => 'Konfiguracja zakończona pomyślnie!',

    'database' => 'Baza danych',
    'selected' => 'Wybrane',
    'mysql_version_is' => 'Wersja MySQL to',
    'database_empty' => 'Baza danych jest pusta',
    'database_not_empty' => 'Instalacja Agora Invoicing wymaga pustej bazy danych, twoja baza danych już zawiera tabele i dane.',
    'mysql_version_required' => 'Zalecamy aktualizację do przynajmniej MySQL 5.6 lub MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Nieudane połączenie z bazą danych.',
    'connected_as' => 'Połączono z bazą danych jako',
    'failed_connection' => 'Nie udało się połączyć z bazą danych.',

];
