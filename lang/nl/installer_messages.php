<?php

return [

    'title' => 'Agora Invoicing Installer',
    'probe' => 'Agora Invoicing Probes',
    'magic_phrase' => 'Wat is de magische zin',
    'server_requirements' => 'Serververeisten',
    'database_setup'=> 'Database-instelling',
    'getting_started' => 'Aan de slag',
    'final' => 'Eind',
    'directory' => 'Directory',
    'permissions' => 'Permissies',
    'requisites' => 'Vereisten',
    'status' => 'Status',
    'php_extensions' => 'PHP-extensies',
    'not_enabled' => 'Niet ingeschakeld',
    'extension_not_enabled' => 'Niet ingeschakeld: Om dit in te schakelen, installeer de extensie op uw server en werk :php_ini_file bij om :extensionName in te schakelen. <a href=":url" target="_blank">Hoe installeer ik PHP-extensies op mijn server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'UIT (Als u Apache gebruikt, zorg er dan voor dat <var><strong>AllowOverride</strong></var> is ingesteld op <var><strong>All</strong></var> in de Apache-configuratie)',
    'rewrite_engine' => 'Rewrite-engine',
    'user_url' => 'Gebruiksvriendelijke URL',

    'host' => 'Host',
    'host_tooltip' => 'Als uw MySQL op dezelfde server als Agora Invoicing is geïnstalleerd, laat het dan localhost zijn',
    'database_name_label' => 'Databasenaam',
    'mysql_port_label' => 'MySQL-poortnummer',
    'mysql_port_tooltip' => 'Poortnummer waarop uw MySQL-server luistert. Standaard is dit 3306',
    'username' => 'Gebruikersnaam',
    'password_label' => 'Wachtwoord',
    'test_prerequisites_message' => 'Deze test controleert de vereisten die nodig zijn om Agora Invoicing te installeren',
    'previous' => 'Vorige',

    'sign_up_as_admin' => 'Aanmelden als Admin',
    'first_name' => 'Voornaam',
    'first_name_required' => 'Voornaam is vereist',
    'last_name' => 'Achternaam',
    'last_name_required' => 'Achternaam is vereist',
    'username_info' => 'Gebruikersnaam kan alleen alfanumerieke tekens, spaties, underscores, streepjes, punten en het @-symbool bevatten.',
    'email' => 'E-mail',
    'email_required' => 'E-mailadres van gebruiker is vereist',
    'password_required' => 'Wachtwoord is vereist',
    'confirm_password' => 'Bevestig wachtwoord',
    'confirm_password_required' => 'Bevestig wachtwoord is vereist',
    'password_requirements' => 'Uw wachtwoord moet bevatten:',
    'password_requirements_list' => [
        'Tussen 8-16 tekens',
        'Hoofdletters (A-Z)',
        'Kleine letters (a-z)',
        'Cijfers (0-9)',
        'Speciale tekens (~*!@$#%_+.?:,{ })',
    ],

// Systeeminformatie
    'system_information' => 'Systeeminformatie',
    'environment' => 'Omgeving',
    'environment_required' => 'Omgeving is vereist',
    'production' => 'Productie',
    'development' => 'Ontwikkeling',
    'testing' => 'Testen',
    'cache_driver' => 'Cache-driver',
    'cache_driver_required' => 'Cache-driver is vereist',
    'file' => 'Bestand',
    'redis' => 'Redis',
    'password' => 'Wachtwoord',

// Redis-configuratie
    'redis_setup' => 'Redis-configuratie',
    'redis_host' => 'Redis-host',
    'redis_port' => 'Redis-poort',
    'redis_password' => 'Redis-wachtwoord',

// Knoppen
    'continue' => 'Doorgaan',

// Einde Configuratie
    'final_setup' => 'Uw Agora Invoicing-toepassing is klaar!',
    'installation_complete' => 'Goed gedaan! U heeft de installatie doorlopen.',

// Meer informatie
    'learn_more' => 'Leer meer',
    'knowledge_base' => 'Kennisbank',
    'email_support' => 'E-mailondersteuning',

// Volgende Stap
    'next_step' => 'Volgende stap',
    'login_button' => 'Inloggen bij facturering',

    'pre_migration_success' => 'Pre-migratie is succesvol getest',
    'migrating_tables' => 'Tabellen migreren in de database',
    'db_connection_error' => 'Databaseverbinding is niet bijgewerkt.',
    'database_setup_success' => 'Database is succesvol ingesteld.',
    'env_file_created' => 'Omgevingsconfiguratiebestand is succesvol aangemaakt',
    'pre_migration_test' => 'Pre-migratietest uitvoeren',

    'redis_host_required' => 'Redis-host is vereist.',
    'redis_password_required' => 'Redis-wachtwoord is vereist.',
    'redis_port_required' => 'Redis-poort is vereist.',
    'password_regex' => 'Wachtwoord moet ten minste 8 tekens bevatten, één hoofdletter, één kleine letter, één cijfer en één speciaal teken.',
    'setup_completed' => 'Instelling succesvol voltooid!',

    'database' => 'Database',
    'selected' => 'Geselecteerd',
    'mysql_version_is' => 'MySQL-versie is',
    'database_empty' => 'Database is leeg',
    'database_not_empty' => 'De installatie van Agora Invoicing vereist een lege database, uw database heeft al tabellen en gegevens.',
    'mysql_version_required' => 'We raden aan om te upgraden naar minimaal MySQL 5.6 of MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Databaseverbinding niet succesvol.',
    'connected_as' => 'Verbonden met de database als',
    'failed_connection' => 'Verbinding met database mislukt.',

];
