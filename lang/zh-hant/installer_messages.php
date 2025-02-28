<?php

return [

    'title' => 'Agora 開票安裝程式',
    'probe' => 'Agora 開票探針',
    'magic_phrase' => '魔法短語是什麼',
    'server_requirements' => '伺服器要求',
    'database_setup' => '資料庫設置',
    'getting_started' => '開始使用',
    'final' => '最後',
    'directory' => '目錄',
    'permissions' => '權限',
    'requisites' => '要求',
    'status' => '狀態',
    'php_extensions' => 'PHP 擴展',
    'not_enabled' => '未啟用',
    'extension_not_enabled' => '未啟用：請安裝此擴展並更新 :php_ini_file 以啟用 :extensionName。<a href=":url" target="_blank">如何在伺服器上安裝 PHP 擴展？</a>',
    'mod_rewrite' => 'Mod 重寫',
    'off_apache' => '關閉（如果您使用的是 Apache，請確保在 Apache 配置中設置 <var><strong>AllowOverride</strong></var> 為 <var><strong>All</strong></var>）',
    'rewrite_engine' => '重寫引擎',
    'user_url' => '用戶友好 URL',

    'host' => '主機',
    'host_tooltip' => '如果您的 MySQL 安裝在與 Agora 開票相同的伺服器上，請設置為 localhost',
    'database_name_label' => '資料庫名稱',
    'mysql_port_label' => 'MySQL 端口號',
    'mysql_port_tooltip' => 'MySQL 伺服器正在監聽的端口號，默認為 3306',
    'username' => '用戶名',
    'password_label' => '密碼',
    'test_prerequisites_message' => '此測試將檢查安裝 Agora 開票所需的先決條件',
    'previous' => '上一頁',

    'sign_up_as_admin' => '註冊為管理員',
    'first_name' => '名字',
    'first_name_required' => '名字是必填的',
    'last_name' => '姓氏',
    'last_name_required' => '姓氏是必填的',
    'username_info' => '用戶名只能包含字母數字字符、空格、下劃線、連字符、句點和 @ 符號。',
    'email' => '電子郵件',
    'email_required' => '用戶電子郵件是必填的',
    'password_required' => '密碼是必填的',
    'confirm_password' => '確認密碼',
    'confirm_password_required' => '確認密碼是必填的',
    'password_requirements' => '您的密碼必須包含：',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => '8到16個字符'],
        ['id' => 'letter', 'text' => '小寫字母 (a-z)'],
        ['id' => 'capital', 'text' => '大寫字母 (A-Z)'],
        ['id' => 'number', 'text' => '數字 (0-9)'],
        ['id' => 'space', 'text' => '特殊字符 (~*!@$#%_+.?:,{ })'],
    ],

    // 系統信息
    'system_information' => '系統信息',
    'environment' => '環境',
    'environment_required' => '環境是必填的',
    'production' => '生產',
    'development' => '開發',
    'testing' => '測試',
    'cache_driver' => '緩存驅動',
    'cache_driver_required' => '緩存驅動是必填的',
    'file' => '文件',
    'redis' => 'Redis',
    'password' => '密碼',

    // Redis 設置
    'redis_setup' => 'Redis 設置',
    'redis_host' => 'Redis 主機',
    'redis_port' => 'Redis 端口',
    'redis_password' => 'Redis 密碼',

    // 按鈕
    'continue' => '繼續',

    // 最後設置
    'final_setup' => '您的 Agora 開票應用程序已準備好！',
    'installation_complete' => '好了，您已經完成了安裝。',

    // 瞭解更多
    'learn_more' => '瞭解更多',
    'knowledge_base' => '知識庫',
    'email_support' => '電子郵件支持',

    // 下一步
    'next_step' => '下一步',
    'login_button' => '登入 Agora 開票',

    'pre_migration_success' => '預遷移測試成功',
    'migrating_tables' => '正在遷移資料庫中的表',
    'db_connection_error' => '資料庫連接未更新。',
    'database_setup_success' => '資料庫已成功設置。',
    'env_file_created' => '環境配置文件已成功創建',
    'pre_migration_test' => '運行預遷移測試',

    'redis_host_required' => 'Redis 主機是必填的。',
    'redis_password_required' => 'Redis 密碼是必填的。',
    'redis_port_required' => 'Redis 端口是必填的。',
    'password_regex' => '密碼必須包含至少 8 個字符、一個大寫字母、一個小寫字母、一個數字和一個特殊字符。',
    'setup_completed' => '設置完成！',

    'database' => '資料庫',
    'selected' => '已選擇',
    'mysql_version_is' => 'MySQL 版本是',
    'database_empty' => '資料庫為空',
    'database_not_empty' => 'Agora 開票安裝需要一個空的資料庫，您的資料庫已經有表和數據。',
    'mysql_version_required' => '我們建議將 MySQL 升級到至少 5.6 版本或 MariaDB 10.3 版本！',
    'database_connection_unsuccessful' => '資料庫連接不成功。',
    'connected_as' => '已以以下身份連接到資料庫',
    'failed_connection' => '無法連接到資料庫。',
    'magic_phrase_not_work' => '您輸入的魔法短語無效。',
    'magic_required' => '魔法短語是必填的。',
    'user_name_regex' => '用戶名必須為 3-20 個字符，並且只能包含字母、數字、空格、下劃線、連字符、句點和 @ 符號。',

];
