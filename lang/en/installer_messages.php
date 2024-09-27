<?php

return [

    'title' => 'Agora Invoicing Installer',
    'probe' => 'Agora Invoicing Probes',
    'magic_phrase' => 'What\'s the magic phrase',
    'server_requirements' => 'Server Requirements',
    'database_setup'=> 'Database Setup',
    'getting_started' => 'Getting Started',
    'final' => 'Final',
    'directory' => 'Directory',
    'permissions' => 'Permissions',
    'requisites' => 'Requisites',
    'status' => 'Status',
    'php_extensions' => 'PHP Extensions',
    'not_enabled' => 'Not Enabled',
    'extension_not_enabled' => 'Not Enabled: To enable this, please install the extension on your server and update :php_ini_file to enable :extensionName. <a href=":url" target="_blank">How to install PHP extensions on my server?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'OFF (If you are using apache, make sure <var><strong>AllowOverride</strong></var> is set to <var><strong>All</strong></var> in apache configuration)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'User friendly URL',

    'host_label' => 'Host',
    'host_tooltip' => 'If your MySQL is installed on the same server as Agora Invoicing, let it be localhost',
    'database_name_label' => 'Database name',
    'mysql_port_label' => 'MySQL port number',
    'mysql_port_tooltip' => 'Port number on which your MySQL server is listening. By default, it is 3306',
    'username' => 'Username',
    'password_label' => 'Password',
    'test_prerequisites_message' => 'This test will check prerequisites required to install Agora Invoicing',
    'previous' => 'Previous',

    'sign_up_as_admin' => 'Sign up as Admin',
    'first_name' => 'First Name',
    'first_name_required' => 'First Name is required',
    'last_name' => 'Last Name',
    'last_name_required' => 'Last Name is required',
    'username_info' => 'Username can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.',
    'email' => 'Email',
    'email_required' => 'User email is required',
    'password_required' => 'Password is required',
    'confirm_password' => 'Confirm Password',
    'confirm_password_required' => 'Confirm Password is required',
    'password_requirements' => 'Your password must have:',
    'password_requirements_list' => [
        'Between 8-16 characters',
        'Uppercase characters (A-Z)',
        'Lowercase characters (a-z)',
        'Numbers (0-9)',
        'Special characters (~*!@$#%_+.?:,{ })',
    ],

    // System Information
    'system_information' => 'System Information',
    'environment' => 'Environment',
    'environment_required' => 'Environment is required',
    'production' => 'Production',
    'development' => 'Development',
    'testing' => 'Testing',
    'cache_driver' => 'Cache Driver',
    'cache_driver_required' => 'Cache Driver is required',
    'file' => 'File',
    'redis' => 'Redis',
    'password' => 'Password',

    // Redis Setup
    'redis_setup' => 'Redis Setup',
    'redis_host' => 'Redis Host',
    'redis_port' => 'Redis Port',
    'redis_password' => 'Redis Password',

    // Buttons
    'continue' => 'Continue',


    // Final Setup
    'final_setup' => 'Your Agora Invoicing Application is Ready!',
    'installation_complete' => 'All right, sparky! You’ve made it through the installation.',

    // Learn More
    'learn_more' => 'Learn More',
    'knowledge_base' => 'Knowledge base',
    'email_support' => 'Email Support',

    // Next Step
    'next_step' => 'Next Step',
    'login_button' => 'Login to Billing',

    'pre_migration_success' => 'Pre migration has been tested successfully',
    'migrating_tables' => 'Migrating tables in database',
    'db_connection_error' => 'Database connection did not update.',
    'database_setup_success' => 'Database has been setup successfully.',
    'env_file_created' => 'Environment configuration file has been created successfully',
    'pre_migration_test' => 'Running pre-migration test',

    'redis_host_required' => 'Redis host is required.',
    'redis_password_required' => 'Redis password is required.',
    'redis_port_required' => 'Redis port is required.',
    'password_regex' => 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.',
    'setup_completed' => 'Setup completed successfully!',

    'database' => 'Database',
    'selected' => 'Selected',
    'mysql_version_is' => 'MySQL version is',
    'database_empty' => 'Database is empty',
    'database_not_empty' => 'Agora Invoicing installation requires an empty database, your database already has tables and data in it.',
    'mysql_version_required' => 'We recommend upgrading to at least MySQL 5.6 or MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Database connection unsuccessful.',
    'connected_as' => 'Connected to database as',
    'failed_connection' => 'Failed to connect to database.',

];
