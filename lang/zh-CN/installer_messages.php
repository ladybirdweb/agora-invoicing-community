<?php

return [

    'title' => 'Agora 发票安装程序',
    'probe' => 'Agora 发票探测器',
    'magic_phrase' => '魔法短语',
    'server_requirements' => '服务器要求',
    'database_setup' => '数据库设置',
    'getting_started' => '入门',
    'final' => '最后',
    'directory' => '目录',
    'permissions' => '权限',
    'requisites' => '必要条件',
    'status' => '状态',
    'php_extensions' => 'PHP 扩展',
    'not_enabled' => '未启用',
    'extension_not_enabled' => '未启用：要启用此功能，请在您的服务器上安装该扩展并更新 :php_ini_file 以启用 :extensionName。<a href=":url" target="_blank">如何在我的服务器上安装 PHP 扩展？</a>',
    'mod_rewrite' => '重写模块',
    'off_apache' => '关闭（如果您使用的是 Apache，请确保 <var><strong>AllowOverride</strong></var> 在 Apache 配置中设置为 <var><strong>All</strong></var>）',
    'rewrite_engine' => '重写引擎',
    'user_url' => '用户友好的 URL',

    'host' => '主机',
    'host_tooltip' => '如果您的 MySQL 安装在与 Agora 发票相同的服务器上，请将其设置为 localhost',
    'database_name_label' => '数据库名称',
    'mysql_port_label' => 'MySQL 端口号',
    'mysql_port_tooltip' => '您的 MySQL 服务器正在监听的端口号。默认是 3306',
    'username' => '用户名',
    'password_label' => '密码',
    'test_prerequisites_message' => '此测试将检查安装 Agora 发票所需的前提条件',
    'previous' => '上一步',

    'sign_up_as_admin' => '注册为管理员',
    'first_name' => '名字',
    'first_name_required' => '名字是必填项',
    'last_name' => '姓氏',
    'last_name_required' => '姓氏是必填项',
    'username_info' => '用户名只能包含字母数字字符、空格、下划线、连字符、句点和 @ 符号。',
    'email' => '电子邮件',
    'email_required' => '用户电子邮件是必填项',
    'password_required' => '密码是必填项',
    'confirm_password' => '确认密码',
    'confirm_password_required' => '确认密码是必填项',
    'password_requirements' => '您的密码必须包含：',
    'password_requirements_list' => [
        '8-16 个字符',
        '大写字母 (A-Z)',
        '小写字母 (a-z)',
        '数字 (0-9)',
        '特殊字符 (~*!@$#%_+.?:,{ })',
    ],

    // 系统信息
    'system_information' => '系统信息',
    'environment' => '环境',
    'environment_required' => '环境是必填项',
    'production' => '生产',
    'development' => '开发',
    'testing' => '测试',
    'cache_driver' => '缓存驱动',
    'cache_driver_required' => '缓存驱动是必填项',
    'file' => '文件',
    'redis' => 'Redis',
    'password' => '密码',

    // Redis 设置
    'redis_setup' => 'Redis 设置',
    'redis_host' => 'Redis 主机',
    'redis_port' => 'Redis 端口',
    'redis_password' => 'Redis 密码',

    // 按钮
    'continue' => '继续',

    // 最终设置
    'final_setup' => '您的 Agora 发票应用程序已准备好！',
    'installation_complete' => '好的，伙计！您已经完成了安装。',

    // 了解更多
    'learn_more' => '了解更多',
    'knowledge_base' => '知识库',
    'email_support' => '电子邮件支持',

    // 下一步
    'next_step' => '下一步',
    'login_button' => '登录到计费',

    'pre_migration_success' => '预迁移已成功测试',
    'migrating_tables' => '正在迁移数据库中的表',
    'db_connection_error' => '数据库连接未更新。',
    'database_setup_success' => '数据库已成功设置。',
    'env_file_created' => '环境配置文件已成功创建',
    'pre_migration_test' => '正在运行预迁移测试',

    'redis_host_required' => 'Redis 主机是必填项。',
    'redis_password_required' => 'Redis 密码是必填项。',
    'redis_port_required' => 'Redis 端口是必填项。',
    'password_regex' => '密码必须包含至少 8 个字符、一个大写字母、一个小写字母、一个数字和一个特殊字符。',
    'setup_completed' => '设置成功完成！',

    'database' => '数据库',
    'selected' => '已选择',
    'mysql_version_is' => 'MySQL 版本是',
    'database_empty' => '数据库为空',
    'database_not_empty' => 'Agora 发票安装需要一个空数据库，您的数据库中已经有表和数据。',
    'mysql_version_required' => '我们建议升级到至少 MySQL 5.6 或 MariaDB 10.3！',
    'database_connection_unsuccessful' => '数据库连接不成功。',
    'connected_as' => '以用户名连接到数据库',
    'failed_connection' => '无法连接到数据库。',

];
