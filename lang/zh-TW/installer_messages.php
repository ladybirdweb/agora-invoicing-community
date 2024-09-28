<?php

return [
    'title' => 'Agora 發票安裝程式',
    'probe' => 'Agora 發票探測器',
    'magic_phrase' => '什麼是魔法短語',
    'server_requirements' => '伺服器需求',
    'database_setup' => '資料庫設置',
    'getting_started' => '開始使用',
    'final' => '完成',
    'directory' => '目錄',
    'permissions' => '權限',
    'requisites' => '必要條件',
    'status' => '狀態',
    'php_extensions' => 'PHP 擴展',
    'not_enabled' => '未啟用',
    'extension_not_enabled' => '未啟用：要啟用此功能，請在伺服器上安裝該擴展並更新 :php_ini_file 以啟用 :extensionName。<a href=":url" target="_blank">如何在我的伺服器上安裝 PHP 擴展？</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => '關閉 (如果您使用的是 Apache，請確保 <var><strong>AllowOverride</strong></var> 在 Apache 配置中設置為 <var><strong>All</strong></var>)',
    'rewrite_engine' => '重寫引擎',
    'user_url' => '使用者友好的網址',

    'host' => '主機',
    'host_tooltip' => '如果您的 MySQL 安裝在與 Agora 發票相同的伺服器上，請使用 localhost',
    'database_name_label' => '資料庫名稱',
    'mysql_port_label' => 'MySQL 端口號碼',
    'mysql_port_tooltip' => 'MySQL 伺服器正在聆聽的端口號，默認為 3306',
    'username' => '用戶名',
    'password_label' => '密碼',
    'test_prerequisites_message' => '此測試將檢查安裝 Agora 發票所需的必要條件',
    'previous' => '上一頁',

    'sign_up_as_admin' => '註冊為管理員',
    'first_name' => '名字',
    'first_name_required' => '名字為必填',
    'last_name' => '姓氏',
    'last_name_required' => '姓氏為必填',
    'username_info' => '用戶名只能包含字母數字字符、空格、下劃線、連字符、句點和 @ 符號。',
    'email' => '電子郵件',
    'email_required' => '用戶電子郵件為必填',
    'password_required' => '密碼為必填',
    'confirm_password' => '確認密碼',
    'confirm_password_required' => '確認密碼為必填',
    'password_requirements' => '您的密碼必須包含：',
    'password_requirements_list' => [
        '8-16 個字符',
        '大寫字母 (A-Z)',
        '小寫字母 (a-z)',
        '數字 (0-9)',
        '特殊字符 (~*!@$#%_+.?:,{ })',
    ],

// 系統信息
    'system_information' => '系統信息',
    'environment' => '環境',
    'environment_required' => '環境為必填',
    'production' => '生產',
    'development' => '開發',
    'testing' => '測試',
    'cache_driver' => '緩存驅動程序',
    'cache_driver_required' => '緩存驅動程序為必填',
    'file' => '檔案',
    'redis' => 'Redis',
    'password' => '密碼',

// Redis 設置
    'redis_setup' => 'Redis 設置',
    'redis_host' => 'Redis 主機',
    'redis_port' => 'Redis 端口',
    'redis_password' => 'Redis 密碼',

// 按鈕
    'continue' => '繼續',

// 最終設置
    'final_setup' => '您的 Agora 發票應用程序已準備好！',
    'installation_complete' => '太好了，火花！您已成功完成安裝。',

// 獲取更多信息
    'learn_more' => '瞭解更多',
    'knowledge_base' => '知識庫',
    'email_support' => '電子郵件支持',

// 下一步
    'next_step' => '下一步',
    'login_button' => '登錄到計費',

    'pre_migration_success' => '預遷移已成功測試',
    'migrating_tables' => '正在遷移資料庫中的表',
    'db_connection_error' => '資料庫連接未更新。',
    'database_setup_success' => '資料庫已成功設置。',
    'env_file_created' => '環境配置文件已成功創建',
    'pre_migration_test' => '正在運行預遷移測試',

    'redis_host_required' => 'Redis 主機為必填。',
    'redis_password_required' => 'Redis 密碼為必填。',
    'redis_port_required' => 'Redis 端口為必填。',
    'password_regex' => '密碼必須至少包含 8 個字符、一個大寫字母、一個小寫字母、一個數字和一個特殊字符。',
    'setup_completed' => '設置成功完成！',

    'database' => '資料庫',
    'selected' => '已選擇',
    'mysql_version_is' => 'MySQL 版本是',
    'database_empty' => '資料庫為空',
    'database_not_empty' => 'Agora 發票安裝需要一個空的資料庫，您的資料庫已經有表和數據。',
    'mysql_version_required' => '我們建議升級到至少 MySQL 5.6 或 MariaDB 10.3！',
    'database_connection_unsuccessful' => '資料庫連接不成功。',
    'connected_as' => '已以用戶身份連接到資料庫',
    'failed_connection' => '無法連接到資料庫。',

];
