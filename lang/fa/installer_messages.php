<?php

return [
    'title' => 'نصب‌کننده صورت‌حساب آگورا',
    'probe' => 'پروب‌های صورت‌حساب آگورا',
    'magic_phrase' => 'عبارت جادویی چیست',
    'server_requirements' => 'نیازمندی‌های سرور',
    'database_setup' => 'راه‌اندازی پایگاه داده',
    'getting_started' => 'شروع کار',
    'final' => 'نهایی',
    'directory' => 'دائره',
    'permissions' => 'مجوزها',
    'requisites' => 'پیش‌نیازها',
    'status' => 'وضعیت',
    'php_extensions' => 'افزونه‌های PHP',
    'not_enabled' => 'فعال نشده',
    'extension_not_enabled' => 'فعال نشده: برای فعال‌سازی این، لطفاً افزونه را بر روی سرور خود نصب کرده و فایل :php_ini_file را برای فعال‌سازی :extensionName به‌روزرسانی کنید. <a href=":url" target="_blank">چگونه می‌توانم افزونه‌های PHP را بر روی سرورم نصب کنم؟</a>',
    'mod_rewrite' => 'مد بازنویسی',
    'off_apache' => 'خاموش (اگر از آپاچی استفاده می‌کنید، مطمئن شوید که <var><strong>AllowOverride</strong></var> در تنظیمات آپاچی به <var><strong>All</strong></var> تنظیم شده است)',
    'rewrite_engine' => 'موتور بازنویسی',
    'user_url' => 'URL دوستانه',

    'host' => 'میزبان',
    'host_tooltip' => 'اگر MySQL شما بر روی همان سرور آگورا نصب شده است، بگذارید localhost باشد',
    'database_name_label' => 'نام پایگاه داده',
    'mysql_port_label' => 'شماره پورت MySQL',
    'mysql_port_tooltip' => 'شماره پورتی که سرور MySQL شما به آن گوش می‌دهد. به طور پیش‌فرض، 3306 است',
    'username' => 'نام کاربری',
    'password_label' => 'گذرواژه',
    'test_prerequisites_message' => 'این آزمایش پیش‌نیازهای مورد نیاز برای نصب آگورا را بررسی می‌کند',
    'previous' => 'قبلی',

    'sign_up_as_admin' => 'ثبت‌نام به عنوان مدیر',
    'first_name' => 'نام',
    'first_name_required' => 'نام الزامی است',
    'last_name' => 'نام خانوادگی',
    'last_name_required' => 'نام خانوادگی الزامی است',
    'username_info' => 'نام کاربری می‌تواند فقط شامل کاراکترهای الفبایی عددی، فضاها، زیرخط‌ها، خط‌تیره‌ها، نقاط و نماد @ باشد.',
    'email' => 'ایمیل',
    'email_required' => 'ایمیل کاربر الزامی است',
    'password_required' => 'گذرواژه الزامی است',
    'confirm_password' => 'تأیید گذرواژه',
    'confirm_password_required' => 'تأیید گذرواژه الزامی است',
    'password_requirements' => 'گذرواژه شما باید دارای:',
    'password_requirements_list' => [
        'بین 8 تا 16 کاراکتر',
        'حروف بزرگ (A-Z)',
        'حروف کوچک (a-z)',
        'اعداد (0-9)',
        'کاراکترهای خاص (~*!@$#%_+.?:,{ })',
    ],

    // اطلاعات سیستم
    'system_information' => 'اطلاعات سیستم',
    'environment' => 'محیط',
    'environment_required' => 'محیط الزامی است',
    'production' => 'تولید',
    'development' => 'توسعه',
    'testing' => 'آزمایش',
    'cache_driver' => 'درایور کش',
    'cache_driver_required' => 'درایور کش الزامی است',
    'file' => 'فایل',
    'redis' => 'ردیس',
    'password' => 'گذرواژه',

    // راه‌اندازی Redis
    'redis_setup' => 'راه‌اندازی ردیوس',
    'redis_host' => 'میزبان ردیوس',
    'redis_port' => 'پورت ردیوس',
    'redis_password' => 'گذرواژه ردیوس',

    // دکمه‌ها
    'continue' => 'ادامه',

    // راه‌اندازی نهایی
    'final_setup' => 'برنامه صورت‌حساب آگورا شما آماده است!',
    'installation_complete' => 'خوب، شما به نصب پایان داده‌اید.',

    // یادگیری بیشتر
    'learn_more' => 'یادگیری بیشتر',
    'knowledge_base' => 'پایگاه دانش',
    'email_support' => 'پشتیبانی ایمیلی',

    // مرحله بعد
    'next_step' => 'مرحله بعد',
    'login_button' => 'ورود به صورت‌حساب',

    'pre_migration_success' => 'آزمایش پیش‌مهاجرت با موفقیت انجام شد',
    'migrating_tables' => 'مهاجرت جداول در پایگاه داده',
    'db_connection_error' => 'اتصال پایگاه داده به‌روز نشد.',
    'database_setup_success' => 'پایگاه داده با موفقیت راه‌اندازی شد.',
    'env_file_created' => 'فایل پیکربندی محیط با موفقیت ایجاد شد',
    'pre_migration_test' => 'در حال اجرای آزمایش پیش‌مهاجرت',

    'redis_host_required' => 'میزبان ردیوس الزامی است.',
    'redis_password_required' => 'گذرواژه ردیوس الزامی است.',
    'redis_port_required' => 'پورت ردیوس الزامی است.',
    'password_regex' => 'گذرواژه باید حداقل 8 کاراکتر، یک حرف بزرگ، یک حرف کوچک، یک عدد و یک کاراکتر خاص داشته باشد.',
    'setup_completed' => 'نصب با موفقیت انجام شد!',

    'database' => 'پایگاه داده',
    'selected' => 'انتخاب شده',
    'mysql_version_is' => 'نسخه MySQL',
    'database_empty' => 'پایگاه داده خالی است',
    'database_not_empty' => 'نصب آگورا نیاز به پایگاه داده خالی دارد، پایگاه داده شما قبلاً جداول و داده‌هایی دارد.',
    'mysql_version_required' => 'ما پیشنهاد می‌کنیم به حداقل MySQL 5.6 یا MariaDB 10.3 ارتقا دهید!',
    'database_connection_unsuccessful' => 'اتصال به پایگاه داده ناموفق بود.',
    'connected_as' => 'متصل به پایگاه داده به عنوان',
    'failed_connection' => 'اتصال به پایگاه داده ناموفق بود.',

];
