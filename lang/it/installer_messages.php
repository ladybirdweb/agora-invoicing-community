<?php

return [

    'title' => 'Installer di Agora Invoicing',
    'probe' => 'Prove di Agora Invoicing',
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
    'not_enabled' => 'Non abilitato',
    'extension_not_enabled' => 'Non abilitato: per abilitare questa funzionalità, installa l\'estensione sul tuo server e aggiorna :php_ini_file per abilitare :extensionName. <a href=":url" target="_blank">Come installare le estensioni PHP sul mio server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'OFF (Se stai usando Apache, assicurati che <var><strong>AllowOverride</strong></var> sia impostato su <var><strong>All</strong></var> nella configurazione di Apache)',
    'rewrite_engine' => 'Motore di riscrittura',
    'user_url' => 'URL amichevole per l\'utente',

    'host' => 'Host',
    'host_tooltip' => 'Se MySQL è installato sullo stesso server di Agora Invoicing, lascialo su localhost',
    'database_name_label' => 'Nome del database',
    'mysql_port_label' => 'Numero di porta MySQL',
    'mysql_port_tooltip' => 'Numero di porta su cui il tuo server MySQL sta ascoltando. Di default è 3306',
    'username' => 'Nome utente',
    'password_label' => 'Password',
    'test_prerequisites_message' => 'Questo test verificherà i prerequisiti necessari per installare Agora Invoicing',
    'previous' => 'Precedente',

    'sign_up_as_admin' => 'Registrati come Amministratore',
    'first_name' => 'Nome',
    'first_name_required' => 'Il nome è obbligatorio',
    'last_name' => 'Cognome',
    'last_name_required' => 'Il cognome è obbligatorio',
    'username_info' => 'Il nome utente può contenere solo caratteri alfanumerici, spazi, trattini bassi, trattini, punti e il simbolo @.',
    'email' => 'Email',
    'email_required' => 'L\'email dell\'utente è obbligatoria',
    'password_required' => 'La password è obbligatoria',
    'confirm_password' => 'Conferma la password',
    'confirm_password_required' => 'La conferma della password è obbligatoria',
    'password_requirements' => 'La tua password deve avere:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Tra 8-16 caratteri'],
        ['id' => 'letter', 'text' => 'Caratteri minuscoli (a-z)'],
        ['id' => 'capital', 'text' => 'Caratteri maiuscoli (A-Z)'],
        ['id' => 'number', 'text' => 'Numeri (0-9)'],
        ['id' => 'space', 'text' => 'Caratteri speciali (~*!@$#%_+.?:,{ })'],
    ],

    // Informazioni sul sistema
    'system_information' => 'Informazioni sul sistema',
    'environment' => 'Ambiente',
    'environment_required' => 'L\'ambiente è obbligatorio',
    'production' => 'Produzione',
    'development' => 'Sviluppo',
    'testing' => 'Test',
    'cache_driver' => 'Driver della cache',
    'cache_driver_required' => 'Il driver della cache è obbligatorio',
    'file' => 'File',
    'redis' => 'Redis',
    'password' => 'Password',

    // Configurazione di Redis
    'redis_setup' => 'Configurazione di Redis',
    'redis_host' => 'Host di Redis',
    'redis_port' => 'Porta di Redis',
    'redis_password' => 'Password di Redis',

    // Pulsanti
    'continue' => 'Continua',

    // Configurazione finale
    'final_setup' => 'La tua applicazione Agora Invoicing è pronta!',
    'installation_complete' => 'Tutto a posto, sparky! Hai completato l\'installazione.',

    // Impara di più
    'learn_more' => 'Scopri di più',
    'knowledge_base' => 'Base di conoscenza',
    'email_support' => 'Supporto via email',

    // Prossimo passo
    'next_step' => 'Passo successivo',
    'login_button' => 'Accedi a Agora Invoicing',

    'pre_migration_success' => 'Il test preliminare della migrazione è stato completato con successo',
    'migrating_tables' => 'Migrazione delle tabelle nel database',
    'db_connection_error' => 'La connessione al database non è stata aggiornata.',
    'database_setup_success' => 'Il database è stato configurato correttamente.',
    'env_file_created' => 'Il file di configurazione dell\'ambiente è stato creato correttamente',
    'pre_migration_test' => 'Esecuzione del test preliminare della migrazione',

    'redis_host_required' => 'L\'host di Redis è obbligatorio.',
    'redis_password_required' => 'La password di Redis è obbligatoria.',
    'redis_port_required' => 'La porta di Redis è obbligatoria.',
    'password_regex' => 'La password deve contenere almeno 8 caratteri, una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale.',
    'setup_completed' => 'Configurazione completata con successo!',

    'database' => 'Database',
    'selected' => 'Selezionato',
    'mysql_version_is' => 'La versione di MySQL è',
    'database_empty' => 'Il database è vuoto',
    'database_not_empty' => 'L\'installazione di Agora Invoicing richiede un database vuoto, il tuo database ha già tabelle e dati al suo interno.',
    'mysql_version_required' => 'Si consiglia di aggiornare a MySQL 5.6 o MariaDB 10.3 almeno!',
    'database_connection_unsuccessful' => 'Connessione al database non riuscita.',
    'connected_as' => 'Connesso al database come',
    'failed_connection' => 'Connessione al database fallita.',
    'magic_phrase_not_work' => 'La frase magica che hai inserito non funziona.',
    'magic_required' => 'La frase magica è obbligatoria.',
    'user_name_regex' => 'Il nome utente deve avere tra i 3 e i 20 caratteri e può contenere solo lettere, numeri, spazi, trattini bassi, trattini, punti e il simbolo @.',

];
