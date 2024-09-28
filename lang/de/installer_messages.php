<?php

return [


    'title' => 'Agora Rechnungsinstaller',
    'probe' => 'Agora Rechnungsprüfungen',
    'magic_phrase' => 'Was ist die magische Phrase',
    'server_requirements' => 'Serveranforderungen',
    'database_setup'=> 'Datenbankeinrichtung',
    'getting_started' => 'Erste Schritte',
    'final' => 'Abschluss',
    'directory' => 'Verzeichnis',
    'permissions' => 'Berechtigungen',
    'requisites' => 'Voraussetzungen',
    'status' => 'Status',
    'php_extensions' => 'PHP-Erweiterungen',
    'not_enabled' => 'Nicht aktiviert',
    'extension_not_enabled' => 'Nicht aktiviert: Um dies zu aktivieren, installieren Sie bitte die Erweiterung auf Ihrem Server und aktualisieren Sie :php_ini_file, um :extensionName zu aktivieren. <a href=":url" target="_blank">Wie installiere ich PHP-Erweiterungen auf meinem Server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'AUS (Wenn Sie Apache verwenden, stellen Sie sicher, dass <var><strong>AllowOverride</strong></var> in der Apache-Konfiguration auf <var><strong>All</strong></var> gesetzt ist)',
    'rewrite_engine' => 'Rewrite-Engine',
    'user_url' => 'Benutzerfreundliche URL',

    'host' => 'Host',
    'host_tooltip' => 'Wenn Ihre MySQL-Datenbank auf demselben Server wie Agora Invoicing installiert ist, lassen Sie es localhost sein',
    'database_name_label' => 'Datenbankname',
    'mysql_port_label' => 'MySQL-Portnummer',
    'mysql_port_tooltip' => 'Portnummer, auf der Ihr MySQL-Server lauscht. Standardmäßig ist dies 3306',
    'username' => 'Benutzername',
    'password_label' => 'Passwort',
    'test_prerequisites_message' => 'Dieser Test überprüft die Voraussetzungen für die Installation von Agora Invoicing',
    'previous' => 'Zurück',

    'sign_up_as_admin' => 'Als Administrator anmelden',
    'first_name' => 'Vorname',
    'first_name_required' => 'Vorname ist erforderlich',
    'last_name' => 'Nachname',
    'last_name_required' => 'Nachname ist erforderlich',
    'username_info' => 'Benutzername kann nur alphanumerische Zeichen, Leerzeichen, Unterstriche, Bindestriche, Punkte und das @-Symbol enthalten.',
    'email' => 'E-Mail',
    'email_required' => 'Benutzer-E-Mail ist erforderlich',
    'password_required' => 'Passwort ist erforderlich',
    'confirm_password' => 'Passwort bestätigen',
    'confirm_password_required' => 'Passwortbestätigung ist erforderlich',
    'password_requirements' => 'Ihr Passwort muss Folgendes haben:',
    'password_requirements_list' => [
        'Zwischen 8-16 Zeichen',
        'Großbuchstaben (A-Z)',
        'Kleinbuchstaben (a-z)',
        'Zahlen (0-9)',
        'Sonderzeichen (~*!@$#%_+.?:,{ })',
    ],

    // System Information
    'system_information' => 'Systeminformationen',
    'environment' => 'Umgebung',
    'environment_required' => 'Umgebung ist erforderlich',
    'production' => 'Produktion',
    'development' => 'Entwicklung',
    'testing' => 'Testen',
    'cache_driver' => 'Cache-Treiber',
    'cache_driver_required' => 'Cache-Treiber ist erforderlich',
    'file' => 'Datei',
    'redis' => 'Redis',
    'password' => 'Passwort',

    // Redis Setup
    'redis_setup' => 'Redis-Einrichtung',
    'redis_host' => 'Redis-Host',
    'redis_port' => 'Redis-Port',
    'redis_password' => 'Redis-Passwort',

    // Buttons
    'continue' => 'Fortsetzen',

    // Final Setup
    'final_setup' => 'Ihre Agora Rechnungsanwendung ist bereit!',
    'installation_complete' => 'Alles klar, Sparky! Sie haben die Installation erfolgreich abgeschlossen.',

    // Learn More
    'learn_more' => 'Erfahren Sie mehr',
    'knowledge_base' => 'Wissensdatenbank',
    'email_support' => 'E-Mail-Support',

    // Next Step
    'next_step' => 'Nächster Schritt',
    'login_button' => 'Anmeldung zur Abrechnung',

    'pre_migration_success' => 'Die Vormigration wurde erfolgreich getestet',
    'migrating_tables' => 'Migration von Tabellen in der Datenbank',
    'db_connection_error' => 'Datenbankverbindung wurde nicht aktualisiert.',
    'database_setup_success' => 'Datenbank wurde erfolgreich eingerichtet.',
    'env_file_created' => 'Umgebungs-Konfigurationsdatei wurde erfolgreich erstellt',
    'pre_migration_test' => 'Führe Vor-Migrations-Test durch',

    'redis_host_required' => 'Redis-Host ist erforderlich.',
    'redis_password_required' => 'Redis-Passwort ist erforderlich.',
    'redis_port_required' => 'Redis-Port ist erforderlich.',
    'password_regex' => 'Das Passwort muss mindestens 8 Zeichen, einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen enthalten.',
    'setup_completed' => 'Einrichtung erfolgreich abgeschlossen!',

    'database' => 'Datenbank',
    'selected' => 'Ausgewählt',
    'mysql_version_is' => 'MySQL-Version ist',
    'database_empty' => 'Datenbank ist leer',
    'database_not_empty' => 'Die Installation von Agora Invoicing erfordert eine leere Datenbank, Ihre Datenbank hat bereits Tabellen und Daten.',
    'mysql_version_required' => 'Wir empfehlen ein Upgrade auf mindestens MySQL 5.6 oder MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Datenbankverbindung war nicht erfolgreich.',
    'connected_as' => 'Verbunden mit der Datenbank als',
    'failed_connection' => 'Verbindung zur Datenbank fehlgeschlagen.',

];
