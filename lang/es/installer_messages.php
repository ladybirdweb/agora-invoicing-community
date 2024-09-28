<?php

return [

    'title' => 'Instalador de Agora Facturación',
    'probe' => 'Pruebas de Agora Facturación',
    'magic_phrase' => '¿Cuál es la frase mágica?',
    'server_requirements' => 'Requisitos del Servidor',
    'database_setup' => 'Configuración de la Base de Datos',
    'getting_started' => 'Comenzando',
    'final' => 'Final',
    'directory' => 'Directorio',
    'permissions' => 'Permisos',
    'requisites' => 'Requisitos',
    'status' => 'Estado',
    'php_extensions' => 'Extensiones de PHP',
    'not_enabled' => 'No habilitado',
    'extension_not_enabled' => 'No habilitado: Para habilitar esto, instale la extensión en su servidor y actualice :php_ini_file para habilitar :extensionName. <a href=":url" target="_blank">¿Cómo instalar extensiones de PHP en mi servidor?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'APAGADO (Si está usando Apache, asegúrese de que <var><strong>AllowOverride</strong></var> esté configurado como <var><strong>All</strong></var> en la configuración de Apache)',
    'rewrite_engine' => 'Motor de Reescritura',
    'user_url' => 'URL amigable para el usuario',

    'host' => 'Host',
    'host_tooltip' => 'Si su MySQL está instalado en el mismo servidor que Agora Facturación, déjelo como localhost',
    'database_name_label' => 'Nombre de la base de datos',
    'mysql_port_label' => 'Número de puerto de MySQL',
    'mysql_port_tooltip' => 'Número de puerto en el que su servidor MySQL está escuchando. Por defecto, es 3306',
    'username' => 'Nombre de usuario',
    'password_label' => 'Contraseña',
    'test_prerequisites_message' => 'Esta prueba verificará los requisitos necesarios para instalar Agora Facturación',
    'previous' => 'Anterior',

    'sign_up_as_admin' => 'Registrarse como Administrador',
    'first_name' => 'Nombre',
    'first_name_required' => 'El nombre es requerido',
    'last_name' => 'Apellido',
    'last_name_required' => 'El apellido es requerido',
    'username_info' => 'El nombre de usuario puede contener solo caracteres alfanuméricos, espacios, guiones bajos, guiones, puntos y el símbolo @.',
    'email' => 'Correo electrónico',
    'email_required' => 'El correo electrónico del usuario es requerido',
    'password_required' => 'La contraseña es requerida',
    'confirm_password' => 'Confirmar contraseña',
    'confirm_password_required' => 'La confirmación de contraseña es requerida',
    'password_requirements' => 'Tu contraseña debe tener:',
    'password_requirements_list' => [
        'Entre 8-16 caracteres',
        'Letras mayúsculas (A-Z)',
        'Letras minúsculas (a-z)',
        'Números (0-9)',
        'Caracteres especiales (~*!@$#%_+.?:,{ })',
    ],

    // System Information
    'system_information' => 'Información del Sistema',
    'environment' => 'Entorno',
    'environment_required' => 'El entorno es requerido',
    'production' => 'Producción',
    'development' => 'Desarrollo',
    'testing' => 'Pruebas',
    'cache_driver' => 'Controlador de Caché',
    'cache_driver_required' => 'El controlador de caché es requerido',
    'file' => 'Archivo',
    'redis' => 'Redis',
    'password' => 'Contraseña',

    // Redis Setup
    'redis_setup' => 'Configuración de Redis',
    'redis_host' => 'Host de Redis',
    'redis_port' => 'Puerto de Redis',
    'redis_password' => 'Contraseña de Redis',

    // Buttons
    'continue' => 'Continuar',

    // Final Setup
    'final_setup' => '¡Tu Aplicación de Agora Facturación está lista!',
    'installation_complete' => '¡Todo listo, Sparky! Has completado la instalación.',

    // Learn More
    'learn_more' => 'Aprender más',
    'knowledge_base' => 'Base de conocimientos',
    'email_support' => 'Soporte por correo electrónico',

    // Next Step
    'next_step' => 'Siguiente Paso',
    'login_button' => 'Iniciar sesión en Facturación',

    'pre_migration_success' => 'La pre-migración se ha probado con éxito',
    'migrating_tables' => 'Migrando tablas en la base de datos',
    'db_connection_error' => 'La conexión a la base de datos no se actualizó.',
    'database_setup_success' => 'La base de datos se ha configurado correctamente.',
    'env_file_created' => 'El archivo de configuración del entorno se ha creado correctamente',
    'pre_migration_test' => 'Ejecutando prueba de pre-migración',

    'redis_host_required' => 'El host de Redis es requerido.',
    'redis_password_required' => 'La contraseña de Redis es requerida.',
    'redis_port_required' => 'El puerto de Redis es requerido.',
    'password_regex' => 'La contraseña debe contener al menos 8 caracteres, una letra mayúscula, una letra minúscula, un número y un carácter especial.',
    'setup_completed' => '¡Configuración completada con éxito!',

    'database' => 'Base de datos',
    'selected' => 'Seleccionado',
    'mysql_version_is' => 'La versión de MySQL es',
    'database_empty' => 'La base de datos está vacía',
    'database_not_empty' => 'La instalación de Agora Facturación requiere una base de datos vacía, su base de datos ya tiene tablas y datos.',
    'mysql_version_required' => '¡Recomendamos actualizar a al menos MySQL 5.6 o MariaDB 10.3!',
    'database_connection_unsuccessful' => 'La conexión a la base de datos no fue exitosa.',
    'connected_as' => 'Conectado a la base de datos como',
    'failed_connection' => 'Error al conectar a la base de datos.',
];
