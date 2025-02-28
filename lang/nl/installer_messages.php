<?php

return [

    'title' => 'Agora Facturering Installateur',
    'probe' => 'Agora Facturering Probes',
    'magic_phrase' => 'Wat is de magische zin',
    'server_requirements' => 'Serververeisten',
    'database_setup' => 'Databaseconfiguratie',
    'getting_started' => 'Aan de slag',
    'final' => 'Finale',
    'directory' => 'Map',
    'permissions' => 'Machtigingen',
    'requisites' => 'Vereisten',
    'status' => 'Status',
    'php_extensions' => 'PHP-extensies',
    'not_enabled' => 'Niet Ingeschakeld',
    'extension_not_enabled' => 'Niet Ingeschakeld: Om dit in te schakelen, installeer de extensie op uw server en werk :php_ini_file bij om :extensionName in te schakelen. <a href=":url" target="_blank">Hoe installeer ik PHP-extensies op mijn server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'UIT (Als u Apache gebruikt, zorg ervoor dat <var><strong>AllowOverride</strong></var> is ingesteld op <var><strong>All</strong></var> in de Apache-configuratie)',
    'rewrite_engine' => 'Herschrijf Engine',
    'user_url' => 'Gebruiksvriendelijke URL',

    'host' => 'Host',
    'host_tooltip' => 'Als uw MySQL op dezelfde server is geïnstalleerd als Agora Facturering, laat dit dan localhost zijn',
    'database_name_label' => 'Databasenaam',
    'mysql_port_label' => 'MySQL poortnummer',
    'mysql_port_tooltip' => 'Poortnummer waarop uw MySQL-server luistert. Standaard is dit 3306',
    'username' => 'Gebruikersnaam',
    'password_label' => 'Wachtwoord',
    'test_prerequisites_message' => 'Deze test controleert de vereisten om Agora Facturering te installeren',
    'previous' => 'Vorige',

    'sign_up_as_admin' => 'Meld je aan als Admin',
    'first_name' => 'Voornaam',
    'first_name_required' => 'Voornaam is vereist',
    'last_name' => 'Achternaam',
    'last_name_required' => 'Achternaam is vereist',
    'username_info' => 'De gebruikersnaam mag alleen alfanumerieke tekens, spaties, onderstrepingstekens, koppelteken, punten en het @-symbool bevatten.',
    'email' => 'E-mail',
    'email_required' => 'E-mail van de gebruiker is vereist',
    'password_required' => 'Wachtwoord is vereist',
    'confirm_password' => 'Bevestig wachtwoord',
    'confirm_password_required' => 'Bevestig wachtwoord is vereist',
    'password_requirements' => 'Je wachtwoord moet het volgende bevatten:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Tussen 8-16 tekens'],
        ['id' => 'letter', 'text' => 'Kleine letters (a-z)'],
        ['id' => 'capital', 'text' => 'Hoofdletters (A-Z)'],
        ['id' => 'number', 'text' => 'Cijfers (0-9)'],
        ['id' => 'space', 'text' => 'Speciale tekens (~*!@$#%_+.?:,{ })'],
    ],

    // Systeem Informatie
    'system_information' => 'Systeeminformatie',
    'environment' => 'Omgeving',
    'environment_required' => 'Omgeving is vereist',
    'production' => 'Productie',
    'development' => 'Ontwikkeling',
    'testing' => 'Testen',
    'cache_driver' => 'Cache Driver',
    'cache_driver_required' => 'Cache Driver is vereist',
    'file' => 'Bestand',
    'redis' => 'Redis',
    'password' => 'Wachtwoord',

    // Redis Configuratie
    'redis_setup' => 'Redis Configuratie',
    'redis_host' => 'Redis Host',
    'redis_port' => 'Redis Poort',
    'redis_password' => 'Redis Wachtwoord',

    // Knoppen
    'continue' => 'Doorgaan',

    // Einde Setup
    'final_setup' => 'Je Agora Facturering Applicatie is Klaar!',
    'installation_complete' => 'Alles is in orde, sparky! Je hebt de installatie doorlopen.',

    // Meer Leren
    'learn_more' => 'Leer meer',
    'knowledge_base' => 'Kennisbank',
    'email_support' => 'E-mailondersteuning',

    // Volgende Stap
    'next_step' => 'Volgende Stap',
    'login_button' => 'Log in bij Agora Facturering',

    'pre_migration_success' => 'Pre-migratie is succesvol getest',
    'migrating_tables' => 'Tabellen migreren in de database',
    'db_connection_error' => 'Databaseverbinding is niet bijgewerkt.',
    'database_setup_success' => 'Database is succesvol ingesteld.',
    'env_file_created' => 'Omgevingsconfiguratiebestand is succesvol aangemaakt',
    'pre_migration_test' => 'Pre-migratie test wordt uitgevoerd',

    'redis_host_required' => 'Redis host is vereist.',
    'redis_password_required' => 'Redis wachtwoord is vereist.',
    'redis_port_required' => 'Redis poort is vereist.',
    'password_regex' => 'Wachtwoord moet minstens 8 tekens bevatten, één hoofdletter, één kleine letter, één cijfer en één speciaal teken.',
    'setup_completed' => 'Setup is succesvol voltooid!',

    'database' => 'Database',
    'selected' => 'Geselecteerd',
    'mysql_version_is' => 'MySQL versie is',
    'database_empty' => 'Database is leeg',
    'database_not_empty' => 'De installatie van Agora Facturering vereist een lege database, uw database bevat al tabellen en gegevens.',
    'mysql_version_required' => 'We raden aan om te upgraden naar ten minste MySQL 5.6 of MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Databaseverbinding niet geslaagd.',
    'connected_as' => 'Verbonden met database als',
    'failed_connection' => 'Verbonden niet gelukt.',
    'magic_phrase_not_work' => 'De magische zin die je hebt ingevoerd werkt niet.',
    'magic_required' => 'De magische zin is vereist.',
    'user_name_regex' => 'De gebruikersnaam moet 3-20 tekens bevatten en mag alleen letters, cijfers, spaties, onderstrepingstekens, koppeltekens, punten en het @-symbool bevatten.',

];
