<?php

return [

    'title' => 'Установщик Agora Invoicing',
    'probe' => 'Пробники Agora Invoicing',
    'magic_phrase' => 'Какова волшебная фраза?',
    'server_requirements' => 'Требования к серверу',
    'database_setup' => 'Настройка базы данных',
    'getting_started' => 'Начало работы',
    'final' => 'Завершение',
    'directory' => 'Каталог',
    'permissions' => 'Права доступа',
    'requisites' => 'Требования',
    'status' => 'Статус',
    'php_extensions' => 'PHP расширения',
    'not_enabled' => 'Не включено',
    'extension_not_enabled' => 'Не включено: чтобы включить это, установите расширение на вашем сервере и обновите :php_ini_file, чтобы включить :extensionName. <a href=":url" target="_blank">Как установить PHP расширения на мой сервер?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'ВЫКЛЮЧЕНО (Если вы используете Apache, убедитесь, что <var><strong>AllowOverride</strong></var> установлено в <var><strong>All</strong></var> в конфигурации Apache)',
    'rewrite_engine' => 'Механизм переписывания',
    'user_url' => 'Дружелюбный URL',

    'host' => 'Хост',
    'host_tooltip' => 'Если ваш MySQL установлен на том же сервере, что и Agora Invoicing, установите значение "localhost"',
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
    'username_info' => 'Имя пользователя может содержать только алфавитные символы, пробелы, подчеркивания, дефисы, точки и символ @.',
    'email' => 'Электронная почта',
    'email_required' => 'Электронная почта обязательна',
    'password_required' => 'Пароль обязателен',
    'confirm_password' => 'Подтвердите пароль',
    'confirm_password_required' => 'Подтверждение пароля обязательно',
    'password_requirements' => 'Ваш пароль должен содержать:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'От 8 до 16 символов'],
        ['id' => 'letter', 'text' => 'Строчные буквы (a-z)'],
        ['id' => 'capital', 'text' => 'Заглавные буквы (A-Z)'],
        ['id' => 'number', 'text' => 'Цифры (0-9)'],
        ['id' => 'space', 'text' => 'Специальные символы (~*!@$#%_+.?:,{ })'],
    ],

    // System Information
    'system_information' => 'Информация о системе',
    'environment' => 'Окружение',
    'environment_required' => 'Окружение обязательно',
    'production' => 'Продакшн',
    'development' => 'Разработка',
    'testing' => 'Тестирование',
    'cache_driver' => 'Кэш-драйвер',
    'cache_driver_required' => 'Кэш-драйвер обязателен',
    'file' => 'Файл',
    'redis' => 'Redis',
    'password' => 'Пароль',

    // Redis Setup
    'redis_setup' => 'Настройка Redis',
    'redis_host' => 'Хост Redis',
    'redis_port' => 'Порт Redis',
    'redis_password' => 'Пароль Redis',

    // Buttons
    'continue' => 'Продолжить',

    // Final Setup
    'final_setup' => 'Ваша приложение Agora Invoicing готово!',
    'installation_complete' => 'Все готово! Вы прошли через установку.',

    // Learn More
    'learn_more' => 'Узнать больше',
    'knowledge_base' => 'База знаний',
    'email_support' => 'Электронная почта поддержки',

    // Next Step
    'next_step' => 'Следующий шаг',
    'login_button' => 'Войти в Agora Invoicing',

    'pre_migration_success' => 'Предварительная миграция прошла успешно',
    'migrating_tables' => 'Миграция таблиц в базе данных',
    'db_connection_error' => 'Ошибка обновления соединения с базой данных.',
    'database_setup_success' => 'База данных успешно настроена.',
    'env_file_created' => 'Файл конфигурации окружения был успешно создан',
    'pre_migration_test' => 'Запуск предварительного теста миграции',

    'redis_host_required' => 'Хост Redis обязателен.',
    'redis_password_required' => 'Пароль Redis обязателен.',
    'redis_port_required' => 'Порт Redis обязателен.',
    'password_regex' => 'Пароль должен содержать хотя бы 8 символов, одну заглавную букву, одну строчную букву, одну цифру и один специальный символ.',
    'setup_completed' => 'Настройка успешно завершена!',

    'database' => 'База данных',
    'selected' => 'Выбрано',
    'mysql_version_is' => 'Версия MySQL',
    'database_empty' => 'База данных пуста',
    'database_not_empty' => 'Установка Agora Invoicing требует пустую базу данных, ваша база данных уже содержит таблицы и данные.',
    'mysql_version_required' => 'Рекомендуется обновить MySQL до версии 5.6 или MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Не удалось подключиться к базе данных.',
    'connected_as' => 'Подключено к базе данных как',
    'failed_connection' => 'Не удалось подключиться к базе данных.',
    'magic_phrase_not_work' => 'Введенная вами волшебная фраза не работает.',
    'magic_required' => 'Волшебная фраза обязательна.',
    'user_name_regex' => 'Имя пользователя должно содержать от 3 до 20 символов и может включать только буквы, цифры, пробелы, подчеркивания, дефисы, точки и символ @.',

];
