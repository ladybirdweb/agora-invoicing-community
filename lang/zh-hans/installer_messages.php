<?php

return [

    'title' => 'Agora开票安装程序',
    'probe' => 'Agora开票探针',
    'magic_phrase' => '魔术短语是什么',
    'server_requirements' => '服务器要求',
    'database_setup' => '数据库设置',
    'getting_started' => '开始使用',
    'final' => '最终',
    'directory' => '目录',
    'permissions' => '权限',
    'requisites' => '要求',
    'status' => '状态',
    'php_extensions' => 'PHP扩展',
    'not_enabled' => '未启用',
    'extension_not_enabled' => '未启用：要启用此功能，请在您的服务器上安装该扩展并更新 :php_ini_file 启用 :extensionName。<a href=":url" target="_blank">如何在我的服务器上安装PHP扩展？</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => '关闭（如果您使用的是Apache，请确保在Apache配置中将<var><strong>AllowOverride</strong></var>设置为<var><strong>All</strong></var>）',
    'rewrite_engine' => '重写引擎',
    'user_url' => '用户友好的URL',

    'host' => '主机',
    'host_tooltip' => '如果您的MySQL与Agora开票安装在同一台服务器上，请使用localhost',
    'database_name_label' => '数据库名称',
    'mysql_port_label' => 'MySQL端口号',
    'mysql_port_tooltip' => '您的MySQL服务器监听的端口号。默认是3306',
    'username' => '用户名',
    'password_label' => '密码',
    'test_prerequisites_message' => '此测试将检查安装Agora开票所需的先决条件',
    'previous' => '上一步',

    'sign_up_as_admin' => '注册为管理员',
    'first_name' => '名字',
    'first_name_required' => '名字是必填项',
    'last_name' => '姓氏',
    'last_name_required' => '姓氏是必填项',
    'username_info' => '用户名只能包含字母数字字符、空格、下划线、连字符、句点和@符号。',
    'email' => '电子邮件',
    'email_required' => '用户电子邮件是必填项',
    'password_required' => '密码是必填项',
    'confirm_password' => '确认密码',
    'confirm_password_required' => '确认密码是必填项',
    'password_requirements' => '您的密码必须包含：',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => '8-16个字符之间'],
        ['id' => 'letter', 'text' => '小写字母（a-z）'],
        ['id' => 'capital', 'text' => '大写字母（A-Z）'],
        ['id' => 'number', 'text' => '数字（0-9）'],
        ['id' => 'space', 'text' => '特殊字符（~*!@$#%_+.?:,{ })'],
    ],

    // 系统信息
    'system_information' => '系统信息',
    'environment' => '环境',
    'environment_required' => '环境是必填项',
    'production' => '生产环境',
    'development' => '开发环境',
    'testing' => '测试环境',
    'cache_driver' => '缓存驱动',
    'cache_driver_required' => '缓存驱动是必填项',
    'file' => '文件',
    'redis' => 'Redis',
    'password' => '密码',

    // Redis设置
    'redis_setup' => 'Redis设置',
    'redis_host' => 'Redis主机',
    'redis_port' => 'Redis端口',
    'redis_password' => 'Redis密码',

    // 按钮
    'continue' => '继续',

    // 最终设置
    'final_setup' => '您的Agora开票应用程序已准备好！',
    'installation_complete' => '好极了，您已完成安装。',

    // 了解更多
    'learn_more' => '了解更多',
    'knowledge_base' => '知识库',
    'email_support' => '电子邮件支持',

    // 下一步
    'next_step' => '下一步',
    'login_button' => '登录Agora开票',

    'pre_migration_success' => '迁移前测试已成功通过',
    'migrating_tables' => '正在迁移数据库中的表',
    'db_connection_error' => '数据库连接未更新。',
    'database_setup_success' => '数据库已成功设置。',
    'env_file_created' => '环境配置文件已成功创建',
    'pre_migration_test' => '运行迁移前测试',

    'redis_host_required' => 'Redis主机是必填项。',
    'redis_password_required' => 'Redis密码是必填项。',
    'redis_port_required' => 'Redis端口是必填项。',
    'password_regex' => '密码必须包含至少8个字符，一个大写字母，一个小写字母，一个数字和一个特殊字符。',
    'setup_completed' => '设置成功完成！',

    'database' => '数据库',
    'selected' => '已选择',
    'mysql_version_is' => 'MySQL版本为',
    'database_empty' => '数据库为空',
    'database_not_empty' => 'Agora开票安装需要一个空数据库，您的数据库中已经有表和数据。',
    'mysql_version_required' => '我们建议升级到至少MySQL 5.6或MariaDB 10.3！',
    'database_connection_unsuccessful' => '数据库连接失败。',
    'connected_as' => '已连接到数据库',
    'failed_connection' => '连接数据库失败。',
    'magic_phrase_not_work' => '您输入的魔术短语无效。',
    'magic_required' => '魔术短语是必填项。',
    'user_name_regex' => '用户名必须是3-20个字符，并且只能包含字母、数字、空格、下划线、连字符、句点和@符号。',

];
