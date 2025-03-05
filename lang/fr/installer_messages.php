<?php

return [

    'title' => 'Installeur Agora Invoicing',
    'probe' => 'Sondes Agora Invoicing',
    'magic_phrase' => 'Quelle est la phrase magique',
    'server_requirements' => 'Exigences du serveur',
    'database_setup' => 'Configuration de la base de données',
    'getting_started' => 'Commencer',
    'final' => 'Final',
    'directory' => 'Répertoire',
    'permissions' => 'Permissions',
    'requisites' => 'Requis',
    'status' => 'Statut',
    'php_extensions' => 'Extensions PHP',
    'not_enabled' => 'Non activé',
    'extension_not_enabled' => 'Non activé : Pour l\'activer, veuillez installer l\'extension sur votre serveur et mettre à jour :php_ini_file pour activer :extensionName. <a href=":url" target="_blank">Comment installer les extensions PHP sur mon serveur ?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'Désactivé (Si vous utilisez Apache, assurez-vous que <var><strong>AllowOverride</strong></var> est défini sur <var><strong>All</strong></var> dans la configuration Apache)',
    'rewrite_engine' => 'Moteur de réécriture',
    'user_url' => 'URL conviviale pour l\'utilisateur',

    'host' => 'Hôte',
    'host_tooltip' => 'Si votre MySQL est installé sur le même serveur que Agora Invoicing, laissez-le en localhost',
    'database_name_label' => 'Nom de la base de données',
    'mysql_port_label' => 'Numéro de port MySQL',
    'mysql_port_tooltip' => 'Numéro de port sur lequel votre serveur MySQL écoute. Par défaut, c\'est 3306',
    'username' => 'Nom d\'utilisateur',
    'password_label' => 'Mot de passe',
    'test_prerequisites_message' => 'Ce test vérifiera les prérequis nécessaires à l\'installation d\'Agora Invoicing',
    'previous' => 'Précédent',

    'sign_up_as_admin' => 'S\'inscrire en tant qu\'administrateur',
    'first_name' => 'Prénom',
    'first_name_required' => 'Le prénom est requis',
    'last_name' => 'Nom de famille',
    'last_name_required' => 'Le nom de famille est requis',
    'username_info' => 'Le nom d\'utilisateur peut contenir uniquement des caractères alphanumériques, des espaces, des underscores, des tirets, des points et le symbole @.',
    'email' => 'Email',
    'email_required' => 'L\'email de l\'utilisateur est requis',
    'password_required' => 'Le mot de passe est requis',
    'confirm_password' => 'Confirmer le mot de passe',
    'confirm_password_required' => 'La confirmation du mot de passe est requise',
    'password_requirements' => 'Votre mot de passe doit contenir :',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Entre 8 et 16 caractères'],
        ['id' => 'letter', 'text' => 'Caractères en minuscules (a-z)'],
        ['id' => 'capital', 'text' => 'Caractères en majuscules (A-Z)'],
        ['id' => 'number', 'text' => 'Chiffres (0-9)'],
        ['id' => 'space', 'text' => 'Caractères spéciaux (~*!@$#%_+.?:,{ })'],
    ],

    // Informations système
    'system_information' => 'Informations système',
    'environment' => 'Environnement',
    'environment_required' => 'L\'environnement est requis',
    'production' => 'Production',
    'development' => 'Développement',
    'testing' => 'Test',
    'cache_driver' => 'Pilote de cache',
    'cache_driver_required' => 'Le pilote de cache est requis',
    'file' => 'Fichier',
    'redis' => 'Redis',
    'password' => 'Mot de passe',

    // Configuration Redis
    'redis_setup' => 'Configuration Redis',
    'redis_host' => 'Hôte Redis',
    'redis_port' => 'Port Redis',
    'redis_password' => 'Mot de passe Redis',

    // Boutons
    'continue' => 'Continuer',

    // Configuration finale
    'final_setup' => 'Votre application Agora Invoicing est prête !',
    'installation_complete' => 'Tout est bon, vous avez terminé l\'installation.',

    // En savoir plus
    'learn_more' => 'En savoir plus',
    'knowledge_base' => 'Base de connaissances',
    'email_support' => 'Support par email',

    // Prochaine étape
    'next_step' => 'Prochaine étape',
    'login_button' => 'Se connecter à Agora Invoicing',

    'pre_migration_success' => 'La pré-migration a été testée avec succès',
    'migrating_tables' => 'Migration des tables dans la base de données',
    'db_connection_error' => 'La connexion à la base de données n\'a pas été mise à jour.',
    'database_setup_success' => 'La base de données a été configurée avec succès.',
    'env_file_created' => 'Le fichier de configuration de l\'environnement a été créé avec succès',
    'pre_migration_test' => 'Exécution du test de pré-migration',

    'redis_host_required' => 'L\'hôte Redis est requis.',
    'redis_password_required' => 'Le mot de passe Redis est requis.',
    'redis_port_required' => 'Le port Redis est requis.',
    'password_regex' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
    'setup_completed' => 'Installation terminée avec succès !',

    'database' => 'Base de données',
    'selected' => 'Sélectionné',
    'mysql_version_is' => 'La version de MySQL est',
    'database_empty' => 'La base de données est vide',
    'database_not_empty' => 'L\'installation d\'Agora Invoicing nécessite une base de données vide, votre base de données contient déjà des tables et des données.',
    'mysql_version_required' => 'Nous vous recommandons de mettre à jour vers MySQL 5.6 ou MariaDB 10.3 au moins !',
    'database_connection_unsuccessful' => 'Connexion à la base de données échouée.',
    'connected_as' => 'Connecté à la base de données en tant que',
    'failed_connection' => 'Échec de la connexion à la base de données.',
    'magic_phrase_not_work' => 'La phrase magique que vous avez saisie ne fonctionne pas.',
    'magic_required' => 'La phrase magique est requise.',
    'user_name_regex' => "Le nom d'utilisateur doit comporter entre 3 et 20 caractères et ne peut contenir que des lettres, des chiffres, des espaces, des underscores, des tirets, des points et le symbole @.",
    'user_name' => "Nom d'utilisateur",
    'user_email' => "E-mail de l'utilisateur",
    'password_not_match' => 'Les mots de passe ne correspondent pas',

    //nouveau
    'installation_check' => 'Ce test vérifiera les prérequis nécessaires pour installer Agora Invoice Community',
    'db_setup_error_1' => "Cela signifie soit que les informations de nom d'utilisateur et de mot de passe sont incorrectes, soit que votre hôte n'est pas accessible.",
    'db_setup_error_2' => 'Êtes-vous sûr d’avoir déjà une base de données existante avec le nom de base de données fourni ?',
    'db_setup_error_3' => 'Êtes-vous sûr d’avoir les privilèges corrects sur la base de données ?',
    'db_setup_error_4' => "Êtes-vous sûr d'avoir le bon nom d'utilisateur et le bon mot de passe ?",
    'db_setup_error_5' => "Êtes-vous sûr d'avoir saisi le bon nom d'hôte ?",
    'db_setup_error_6' => 'Êtes-vous sûr que le serveur de base de données est en cours d’exécution ?',
    'db_setup_error_7' => "Impossible de tester la connexion à la base de données. Veuillez vous assurer que votre serveur de base de données est en cours d'exécution et que PHP fonctionne avec les sessions.",
    'db_setup_error_8' => 'Si vous avez corrigé toutes les erreurs.',
    'instruction' => "Si vous ne savez pas ce que ces termes signifient, vous devriez probablement contacter votre hébergeur. Si vous avez encore besoin d'aide, vous pouvez toujours visiter",
    'ok' => 'Ok',
    'all_ok' => 'Tout est Ok',
    'warning' => 'Avertissement',
    'error' => 'Erreur',
    'instruction_2' => "Ce n'est pas un problème majeur, mais il est recommandé d'avoir cette fonctionnalité installée pour que certaines fonctionnalités fonctionnent correctement.",
    'instruction_3' => 'Agora Invoice Community nécessite cette fonctionnalité et ne peut pas fonctionner sans elle.',
    'click_here' => 'Cliquez ici',
    'continue_installation_process' => 'pour continuer le processus d’installation.',
    'database_details' => 'Entrez les détails de connexion à votre base de données.',
    'host' => 'Hôte',
    'sql_port' => 'Numéro de port MySQL',
    'database_name' => 'Nom de la base de données',
    'finishing_setup' => 'Veuillez patienter, finalisation de l’installation...',
    'lic_agreement_1' => 'Veuillez lire attentivement cet accord de licence logiciel avant de poursuivre ou d’utiliser le logiciel. En cliquant sur le bouton "Accepter", vous acceptez d’être lié par cet accord. Si vous n’acceptez pas tous les termes de cet accord, arrêtez le processus d’installation et quittez.',
    'lic_agreement_2' => 'CONTRAT DE LICENCE UTILISATEUR FINAL',
    'lic_agreement_3' => "CE « CONTRAT DE LICENCE UTILISATEUR FINAL » (LE « CLUF ») EST UN CONTRAT JURIDIQUE ENTRE L'INDIVIDU OU L'ENTITÉ LÉGALE OU L'ASSOCIATION PRÉVOYANT D'UTILISER LE LOGICIEL (« VOUS » OU « CLIENT ») ET FAVEO. EN VOUS INSCRIVANT POUR LE LOGICIEL OU EN UTILISANT LE LOGICIEL, VOUS DÉCLAREZ, GARANTISSEZ ET ACCEPTEZ AVOIR LU, COMPRIS ET ACCEPTÉ D'ÊTRE LIÉ PAR LES TERMES DU CLUF. SI VOUS N’ACCEPTEZ PAS D’ÊTRE LIÉ PAR LE CLUF, OU SI VOUS N’AVEZ PAS L’AUTORITÉ POUR ENGAGER LE CLIENT AU CLUF, VOUS NE POUVEZ PAS UTILISER LE LOGICIEL.",
    'definition' => 'Définitions',
    'i_accept' => "J'accepte les",
    'enter_license' => 'Veuillez entrer votre code de licence pour',
    'find_license_in_billing' => 'Vous pouvez trouver votre code de licence dans notre portail de facturation',
    'sign_up_as_admin' => "S'inscrire en tant qu'administrateur",
    'system_information' => 'Informations système',
    'close' => 'Fermer',

];
