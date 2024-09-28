<?php

return [
    'title' => 'Installer di Agora Invoicing',
    'probe' => 'Probe di Agora Invoicing',
    'magic_phrase' => 'Qual è la frase magica',
    'server_requirements' => 'Requisiti del server',
    'database_setup' => 'Impostazione del database',
    'getting_started' => 'Iniziare',
    'final' => 'Finale',
    'directory' => 'Directory',
    'permissions' => 'Permessi',
    'requisites' => 'Requisiti',
    'status' => 'Stato',
    'php_extensions' => 'Estensioni PHP',
    'not_enabled' => 'Non Abilitato',
    'extension_not_enabled' => 'Non Abilitato: Per abilitare questo, si prega di installare l\'estensione sul server e aggiornare :php_ini_file per abilitare :extensionName. <a href=":url" target="_blank">Come installare le estensioni PHP sul mio server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'SPENTO (Se stai usando Apache, assicurati che <var><strong>AllowOverride</strong></var> sia impostato su <var><strong>All</strong></var> nella configurazione di Apache)',
    'rewrite_engine' => 'Motore di riscrittura',
    'user_url' => 'URL amichevole per l\'utente',

    'host' => 'Host',
    'host_tooltip' => 'Se MySQL è installato sullo stesso server di Agora Invoicing, lascialo su localhost',
    'database_name_label' => 'Nome del database',
    'mysql_port_label' => 'Numero di porta MySQL',
    'mysql_port_tooltip' => 'Numero di porta su cui il server MySQL sta ascoltando. Per impostazione predefinita, è 3306',
    'username' => 'Nome utente',
    'password_label' => 'Password',
    'test_prerequisites_message' => 'Questo test verificherà i prerequisiti necessari per installare Agora Invoicing',
    'previous' => 'Precedente',

    'sign_up_as_admin' => 'Registrati come Amministratore',
    'first_name' => 'Nome',
    'first_name_required' => 'Il nome è richiesto',
    'last_name' => 'Cognome',
    'last_name_required' => 'Il cognome è richiesto',
    'username_info' => 'Il nome utente può contenere solo caratteri alfanumerici, spazi, underscore, trattini, punti e simbolo @.',
    'email' => 'Email',
    'email_required' => 'L\'email dell\'utente è richiesta',
    'password_required' => 'La password è richiesta',
    'confirm_password' => 'Conferma Password',
    'confirm_password_required' => 'La conferma della password è richiesta',
    'password_requirements' => 'La tua password deve avere:',
    'password_requirements_list' => [
        'Tra 8-16 caratteri',
        'Caratteri maiuscoli (A-Z)',
        'Caratteri minuscoli (a-z)',
        'Numeri (0-9)',
        'Caratteri speciali (~*!@$#%_+.?:,{ })',
    ],

    // Informazioni di sistema
    'system_information' => 'Informazioni di sistema',
    'environment' => 'Ambiente',
    'environment_required' => 'L\'ambiente è richiesto',
    'production' => 'Produzione',
    'development' => 'Sviluppo',
    'testing' => 'Test',
    'cache_driver' => 'Driver della cache',
    'cache_driver_required' => 'Il driver della cache è richiesto',
    'file' => 'File',
    'redis' => 'Redis',
    'password' => 'Password',

    // Configurazione Redis
    'redis_setup' => 'Configurazione Redis',
    'redis_host' => 'Host Redis',
    'redis_port' => 'Porta Redis',
    'redis_password' => 'Password Redis',

    // Pulsanti
    'continue' => 'Continua',

    // Configurazione finale
    'final_setup' => 'La tua applicazione Agora Invoicing è pronta!',
    'installation_complete' => 'Tutto bene, amico! Hai completato l\'installazione.',

    // Scopri di più
    'learn_more' => 'Scopri di più',
    'knowledge_base' => 'Base di conoscenza',
    'email_support' => 'Supporto via email',

    // Passo successivo
    'next_step' => 'Passo successivo',
    'login_button' => 'Accedi alla fatturazione',

    'pre_migration_success' => 'La pre-migrazione è stata testata con successo',
    'migrating_tables' => 'Migrazione delle tabelle nel database',
    'db_connection_error' => 'La connessione al database non è stata aggiornata.',
    'database_setup_success' => 'Il database è stato impostato correttamente.',
    'env_file_created' => 'Il file di configurazione dell\'ambiente è stato creato con successo',
    'pre_migration_test' => 'Esecuzione del test di pre-migrazione',

    'redis_host_required' => 'L\'host Redis è richiesto.',
    'redis_password_required' => 'La password Redis è richiesta.',
    'redis_port_required' => 'La porta Redis è richiesta.',
    'password_regex' => 'La password deve contenere almeno 8 caratteri, una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale.',
    'setup_completed' => 'Configurazione completata con successo!',

    'database' => 'Database',
    'selected' => 'Selezionato',
    'mysql_version_is' => 'La versione di MySQL è',
    'database_empty' => 'Il database è vuoto',
    'database_not_empty' => 'L\'installazione di Agora Invoicing richiede un database vuoto, il tuo database ha già tabelle e dati al suo interno.',
    'mysql_version_required' => 'Ti consigliamo di aggiornare ad almeno MySQL 5.6 o MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Connessione al database non riuscita.',
    'connected_as' => 'Connesso al database come',
    'failed_connection' => 'Connessione al database fallita.',

];
