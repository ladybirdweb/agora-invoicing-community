<?php

return [

    'title' => 'Installateur Agora Invoicing',
    'probe' => 'Sondes Agora Invoicing',
    'magic_phrase' => 'Quelle est la phrase magique',
    'server_requirements' => 'Exigences du serveur',
    'database_setup'=> 'Configuration de la base de données',
    'getting_started' => 'Premiers pas',
    'final' => 'Final',
    'directory' => 'Répertoire',
    'permissions' => 'Permissions',
    'requisites' => 'Requis',
    'status' => 'Statut',
    'php_extensions' => 'Extensions PHP',
    'not_enabled' => 'Non activé',
    'extension_not_enabled' => 'Non activé : Pour l’activer, veuillez installer l’extension sur votre serveur et mettre à jour :php_ini_file pour activer :extensionName. <a href=":url" target="_blank">Comment installer les extensions PHP sur mon serveur ?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'DÉSACTIVÉ (Si vous utilisez Apache, assurez-vous que <var><strong>AllowOverride</strong></var> est réglé sur <var><strong>Tous</strong></var> dans la configuration d’Apache)',
    'rewrite_engine' => 'Moteur de réécriture',
    'user_url' => 'URL conviviale',

    'host' => 'Hôte',
    'host_tooltip' => 'Si votre MySQL est installé sur le même serveur qu’Agora Invoicing, laissez-le en localhost',
    'database_name_label' => 'Nom de la base de données',
    'mysql_port_label' => 'Numéro de port MySQL',
    'mysql_port_tooltip' => 'Numéro de port sur lequel votre serveur MySQL écoute. Par défaut, c’est le 3306',
    'username' => 'Nom d’utilisateur',
    'password_label' => 'Mot de passe',
    'test_prerequisites_message' => 'Ce test vérifiera les prérequis nécessaires à l’installation d’Agora Invoicing',
    'previous' => 'Précédent',

    'sign_up_as_admin' => 'S’inscrire en tant qu’administrateur',
    'first_name' => 'Prénom',
    'first_name_required' => 'Le prénom est requis',
    'last_name' => 'Nom',
    'last_name_required' => 'Le nom est requis',
    'username_info' => 'Le nom d’utilisateur ne peut contenir que des caractères alphanumériques, des espaces, des traits de soulignement, des tirets, des points et le symbole @.',
    'email' => 'Email',
    'email_required' => 'L’email de l’utilisateur est requis',
    'password_required' => 'Le mot de passe est requis',
    'confirm_password' => 'Confirmer le mot de passe',
    'confirm_password_required' => 'La confirmation du mot de passe est requise',
    'password_requirements' => 'Votre mot de passe doit contenir :',
    'password_requirements_list' => [
        'Entre 8 et 16 caractères',
        'Caractères majuscules (A-Z)',
        'Caractères minuscules (a-z)',
        'Chiffres (0-9)',
        'Caractères spéciaux (~*!@$#%_+.?:,{ })',
    ],

// Informations système
    'system_information' => 'Informations système',
    'environment' => 'Environnement',
    'environment_required' => 'L’environnement est requis',
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
    'installation_complete' => 'Tout est en ordre, vous avez réussi l’installation.',

// En savoir plus
    'learn_more' => 'En savoir plus',
    'knowledge_base' => 'Base de connaissances',
    'email_support' => 'Support par email',

// Étape suivante
    'next_step' => 'Étape suivante',
    'login_button' => 'Connexion à la facturation',

    'pre_migration_success' => 'La pré-migration a été testée avec succès',
    'migrating_tables' => 'Migration des tables dans la base de données',
    'db_connection_error' => 'La connexion à la base de données n’a pas été mise à jour.',
    'database_setup_success' => 'La base de données a été configurée avec succès.',
    'env_file_created' => 'Le fichier de configuration de l’environnement a été créé avec succès',
    'pre_migration_test' => 'Exécution du test de pré-migration',

    'redis_host_required' => 'L’hôte Redis est requis.',
    'redis_password_required' => 'Le mot de passe Redis est requis.',
    'redis_port_required' => 'Le port Redis est requis.',
    'password_regex' => 'Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
    'setup_completed' => 'Configuration terminée avec succès !',

    'database' => 'Base de données',
    'selected' => 'Sélectionné',
    'mysql_version_is' => 'La version de MySQL est',
    'database_empty' => 'La base de données est vide',
    'database_not_empty' => 'L’installation d’Agora Invoicing nécessite une base de données vide, votre base de données a déjà des tables et des données.',
    'mysql_version_required' => 'Nous vous recommandons de mettre à niveau vers au moins MySQL 5.6 ou MariaDB 10.3 !',
    'database_connection_unsuccessful' => 'Connexion à la base de données non réussie.',
    'connected_as' => 'Connecté à la base de données en tant que',
    'failed_connection' => 'Échec de la connexion à la base de données.',

];
