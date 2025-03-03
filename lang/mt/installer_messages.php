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
    'installer_check' => 'Dan it-test se jikkontrolla l-prekondizzjonijiet meħtieġa biex jinbena l-Installazzjoni ta\' Network Discovery.',
    'db_setup_error_1' => 'Dan ifisser li l-informazzjoni tas-username u password hija inkorretta jew li l-host tiegħek mhux reachabl.',
    'db_setup_error_2' => 'Trid tkun żgur li għandek database eżistenti bl-isem tad-database li pprovdejt?',
    'db_setup_error_3' => 'Trid tkun żgur li għandek il-privileġġi korretti għall-database?',
    'db_setup_error_4' => 'Trid tkun żgur li għandek l-username u password korretti?',
    'db_setup_error_5' => 'Trid tkun żgur li d-dettalji tal-host huma korretti?',
    'db_setup_error_6' => 'Trid tkun żgur li s-server tad-database qed jaħdem?',
    'db_setup_error_7' => 'Ma nistgħux inittestjaw il-konnessjoni mad-database. Żomm f\'moħħok li l-server tad-database għandu jkun attiv u PHP għandu jaħdem mal-session.',
    'db_setup_error_8' => 'Jekk tissolva l-iżbalji kollha.',
    'instruction' => 'Jekk m\'intix ċert x\'inhu dan it-terminu, huwa rakkomandat li tikkuntattja lill-host tiegħek. Jekk għadek bżonn għajnuna tista\' dejjem iżżur',
    'ok' => 'Ok',
    'all_ok' => 'Kollox Ok',
    'warning' => 'Avviż',
    'error' => 'Żball',
    'instruction_2' => 'Dan mhuwiex ostakolu, iżda huwa rrakkomandat li jkollok dan installat għal xi karatteristiċi biex jaħdmu.',
    'instruction_3' => 'Agora Invoice Community jeħtieġ dan il-karatteristika u ma tistax taħdem mingħajrha.',
    'click_here' => 'Ikklikja hawn',
    'continue_installation_process' => 'Biex tkompli l-proċess tal-installazzjoni.',
    'database_details' => 'Daħħal id-dettalji tal-konnessjoni tad-database tiegħek.',
    'host' => 'Host',
    'sql_port' => 'Numru tal-port MySQL',
    'database_name' => 'Isem tad-database',
    'finishing_setup' => 'Jekk jogħġbok stenna, qed tlesti l-setup...',
    'lic_agreement_1' => 'Jekk jogħġbok aqra dan il-ftehim tal-liċenzja tas-softwer bir-reqqa qabel ma tkompli jew tuża s-softwer. Billi tikklikkja fuq il-buttuna "Aċċetta", qed taqbel li tkun imqabbad għal dan il-ftehim.',
    'lic_agreement_2' => 'FTEHIM TAL-LIĊENZA TAL-UŻAM END-USER',
    'lic_agreement_3' => 'Dan il-"FTEHIM TAL-LIĊENZA TAL-UŻAM END-USER" ("EULA") HUWA FTEHIM LEGALI BEJN L-INDIVIDWU JEKK QED TISTA\'TUŻA S-SOFTWER ("INTI" JEW "KLIENT") U FAVEO.',
    'definition' => 'Definizzjonijiet',
    'i_accept' => 'Naċċetta',
    'enter_license' => 'Daħħal il-Kodiċi tal-Liċenzja tiegħek',
    'find_license_in_billing' => 'Tista\'ssib il-kodiċi tal-liċenzja tiegħek fil-portal tal-billing tagħna.',
    'sign_up_as_admin' => 'Irreġistra bħala amministratur',
    'system_information' => 'Informazzjoni tas-Sistema',
    'close' => 'Ibgħat',

];
