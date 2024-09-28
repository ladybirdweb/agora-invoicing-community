<?php

return [

    'title' => 'Установщик Agora Invoicing',
    'probe' => 'Пробники Agora Invoicing',
    'magic_phrase' => 'Какова волшебная фраза',
    'server_requirements' => 'Требования к серверу',
    'database_setup' => 'Настройка базы данных',
    'getting_started' => 'Начало работы',
    'final' => 'Завершение',
    'directory' => 'Каталог',
    'permissions' => 'Разрешения',
    'requisites' => 'Требования',
    'status' => 'Статус',
    'php_extensions' => 'PHP расширения',
    'not_enabled' => 'Не включено',
    'extension_not_enabled' => 'Не включено: Чтобы включить это, пожалуйста, установите расширение на вашем сервере и обновите :php_ini_file для активации :extensionName. <a href=":url" target="_blank">Как установить PHP расширения на моем сервере?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'ВЫКЛ (Если вы используете Apache, убедитесь, что <var><strong>AllowOverride</strong></var> установлен на <var><strong>All</strong></var> в конфигурации Apache)',
    'rewrite_engine' => 'Модуль перезаписи',
    'user_url' => 'Дружественный URL',

    'host' => 'Хост',
    'host_tooltip' => 'Если MySQL установлен на том же сервере, что и Agora Invoicing, оставьте localhost',
    'database_name_label' => 'Имя базы данных',
    'mysql_port_label' => 'Номер порта MySQL',
    'mysql_port_tooltip' => 'Номер порта, на котором работает ваш сервер MySQL. По умолчанию это 3306',
    'username' => 'Имя пользователя',
    'password_label' => 'Пароль',
    'test_prerequisites_message' => 'Этот тест проверит предварительные требования для установки Agora Invoicing',
    'previous' => 'Предыдущий',

    'sign_up_as_admin' => 'Зарегистрироваться как администратор',
    'first_name' => 'Имя',
    'first_name_required' => 'Имя обязательно',
    'last_name' => 'Фамилия',
    'last_name_required' => 'Фамилия обязательна',
    'username_info' => 'Имя пользователя может содержать только алфавитно-цифровые символы, пробелы, подчеркивания, дефисы, точки и символ @.',
    'email' => 'Электронная почта',
    'email_required' => 'Электронная почта пользователя обязательна',
    'password_required' => 'Пароль обязателен',
    'confirm_password' => 'Подтвердите пароль',
    'confirm_password_required' => 'Подтверждение пароля обязательно',
    'password_requirements' => 'Ваш пароль должен содержать:',
    'password_requirements_list' => [
        'От 8 до 16 символов',
        'Заглавные буквы (A-Z)',
        'Строчные буквы (a-z)',
        'Цифры (0-9)',
        'Специальные символы (~*!@$#%_+.?:,{ })',
    ],

// Системная информация
    'system_information' => 'Системная информация',
    'environment' => 'Окружение',
    'environment_required' => 'Окружение обязательно',
    'production' => 'Производственное',
    'development' => 'Разработка',
    'testing' => 'Тестирование',
    'cache_driver' => 'Драйвер кэша',
    'cache_driver_required' => 'Драйвер кэша обязателен',
    'file' => 'Файл',
    'redis' => 'Redis',
    'password' => 'Пароль',

// Настройка Redis
    'redis_setup' => 'Настройка Redis',
    'redis_host' => 'Хост Redis',
    'redis_port' => 'Порт Redis',
    'redis_password' => 'Пароль Redis',

// Кнопки
    'continue' => 'Продолжить',

// Финальная настройка
    'final_setup' => 'Ваше приложение Agora Invoicing готово!',
    'installation_complete' => 'Отлично, вы успешно завершили установку.',

// Узнать больше
    'learn_more' => 'Узнать больше',
    'knowledge_base' => 'База знаний',
    'email_support' => 'Поддержка по электронной почте',

// Следующий шаг
    'next_step' => 'Следующий шаг',
    'login_button' => 'Войти в Billing',

    'pre_migration_success' => 'Предварительная миграция успешно протестирована',
    'migrating_tables' => 'Миграция таблиц в базе данных',
    'db_connection_error' => 'Ошибка обновления подключения к базе данных.',
    'database_setup_success' => 'База данных успешно настроена.',
    'env_file_created' => 'Файл конфигурации окружения успешно создан',
    'pre_migration_test' => 'Запуск предварительного теста миграции',

    'redis_host_required' => 'Хост Redis обязателен.',
    'redis_password_required' => 'Пароль Redis обязателен.',
    'redis_port_required' => 'Порт Redis обязателен.',
    'password_regex' => 'Пароль должен содержать не менее 8 символов, одну заглавную букву, одну строчную букву, одну цифру и один специальный символ.',
    'setup_completed' => 'Настройка успешно завершена!',

    'database' => 'База данных',
    'selected' => 'Выбрано',
    'mysql_version_is' => 'Версия MySQL',
    'database_empty' => 'База данных пуста',
    'database_not_empty' => 'Установка Agora Invoicing требует пустую базу данных, ваша база данных уже содержит таблицы и данные.',
    'mysql_version_required' => 'Рекомендуем обновиться до MySQL не ниже 5.6 или MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Не удалось подключиться к базе данных.',
    'connected_as' => 'Подключено к базе данных как',
    'failed_connection' => 'Не удалось подключиться к базе данных.',

];
