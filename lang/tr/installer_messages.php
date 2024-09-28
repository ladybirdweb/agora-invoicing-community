<?php

return [

    'title' => 'Agora Faturalama Yükleyicisi',
    'probe' => 'Agora Faturalama Provaları',
    'magic_phrase' => 'Sihirli cümle nedir',
    'server_requirements' => 'Sunucu Gereksinimleri',
    'database_setup'=> 'Veritabanı Kurulumu',
    'getting_started' => 'Başlarken',
    'final' => 'Son',
    'directory' => 'Dizin',
    'permissions' => 'İzinler',
    'requisites' => 'Gereksinimler',
    'status' => 'Durum',
    'php_extensions' => 'PHP Uzantıları',
    'not_enabled' => 'Etkin Değil',
    'extension_not_enabled' => 'Etkin Değil: Bunu etkinleştirmek için lütfen sunucunuza uzantıyı yükleyin ve :php_ini_file dosyasını :extensionName uzantısını etkinleştirmek için güncelleyin. <a href=":url" target="_blank">Sunucumda PHP uzantıları nasıl yüklenir?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'KAPALI (Eğer Apache kullanıyorsanız, apache yapılandırmasında <var><strong>AllowOverride</strong></var> ayarının <var><strong>All</strong></var> olarak ayarlandığından emin olun)',
    'rewrite_engine' => 'Yazma Motoru',
    'user_url' => 'Kullanıcı dostu URL',

    'host' => 'Ana Bilgisayar',
    'host_tooltip' => 'Eğer MySQL sunucunuz Agora Faturalama ile aynı sunucuda kuruluysa, localhost olarak bırakın',
    'database_name_label' => 'Veritabanı adı',
    'mysql_port_label' => 'MySQL bağlantı noktası numarası',
    'mysql_port_tooltip' => 'MySQL sunucunuzun dinlediği bağlantı noktası numarası. Varsayılan olarak 3306\'dır',
    'username' => 'Kullanıcı Adı',
    'password_label' => 'Şifre',
    'test_prerequisites_message' => 'Bu test, Agora Faturalama\'yı kurmak için gereken ön koşulları kontrol edecektir',
    'previous' => 'Önceki',

    'sign_up_as_admin' => 'Yönetici olarak kaydol',
    'first_name' => 'Ad',
    'first_name_required' => 'Ad gereklidir',
    'last_name' => 'Soyad',
    'last_name_required' => 'Soyad gereklidir',
    'username_info' => 'Kullanıcı adı yalnızca alfasayısal karakterler, boşluklar, alt çizgiler, tireler, noktalar ve @ sembolü içerebilir.',
    'email' => 'E-posta',
    'email_required' => 'Kullanıcı e-postası gereklidir',
    'password_required' => 'Şifre gereklidir',
    'confirm_password' => 'Şifreyi Onayla',
    'confirm_password_required' => 'Şifreyi onaylamak gereklidir',
    'password_requirements' => 'Şifreniz aşağıdakilere sahip olmalıdır:',
    'password_requirements_list' => [
        '8-16 karakter arasında',
        'Büyük harf (A-Z)',
        'Küçük harf (a-z)',
        'Rakamlar (0-9)',
        'Özel karakterler (~*!@$#%_+.?:,{ })',
    ],

// Sistem Bilgileri
    'system_information' => 'Sistem Bilgisi',
    'environment' => 'Ortam',
    'environment_required' => 'Ortam gereklidir',
    'production' => 'Üretim',
    'development' => 'Geliştirme',
    'testing' => 'Test',
    'cache_driver' => 'Önbellek Sürücüsü',
    'cache_driver_required' => 'Önbellek Sürücüsü gereklidir',
    'file' => 'Dosya',
    'redis' => 'Redis',
    'password' => 'Şifre',

// Redis Kurulumu
    'redis_setup' => 'Redis Kurulumu',
    'redis_host' => 'Redis Ana Bilgisi',
    'redis_port' => 'Redis Bağlantı Noktası',
    'redis_password' => 'Redis Şifresi',

// Düğmeler
    'continue' => 'Devam et',

// Son Kurulum
    'final_setup' => 'Agora Faturalama Uygulamanız Hazır!',
    'installation_complete' => 'Her şey yolunda, sparky! Kurulumdan başarıyla geçtiniz.',

// Daha Fazla Bilgi Edin
    'learn_more' => 'Daha Fazla Bilgi Edin',
    'knowledge_base' => 'Bilgi Tabanı',
    'email_support' => 'E-posta Desteği',

// Sonraki Adım
    'next_step' => 'Sonraki Adım',
    'login_button' => 'Faturalama Giriş',

    'pre_migration_success' => 'Ön göç başarıyla test edildi',
    'migrating_tables' => 'Veritabanındaki tabloları taşıyor',
    'db_connection_error' => 'Veritabanı bağlantısı güncellenmedi.',
    'database_setup_success' => 'Veritabanı başarıyla kuruldu.',
    'env_file_created' => 'Ortam yapılandırma dosyası başarıyla oluşturuldu',
    'pre_migration_test' => 'Ön göç testi çalıştırılıyor',

    'redis_host_required' => 'Redis ana bilgisini girmek gereklidir.',
    'redis_password_required' => 'Redis şifresi gereklidir.',
    'redis_port_required' => 'Redis bağlantı noktası gereklidir.',
    'password_regex' => 'Şifre en az 8 karakter, bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir.',
    'setup_completed' => 'Kurulum başarıyla tamamlandı!',

    'database' => 'Veritabanı',
    'selected' => 'Seçilen',
    'mysql_version_is' => 'MySQL sürümü',
    'database_empty' => 'Veritabanı boş',
    'database_not_empty' => 'Agora Faturalama kurulumu boş bir veritabanı gerektirir, veritabanınızda zaten tablolar ve veriler bulunmaktadır.',
    'mysql_version_required' => 'En az MySQL 5.6 veya MariaDB 10.3\'e yükseltmenizi öneririz!',
    'database_connection_unsuccessful' => 'Veritabanı bağlantısı başarısız.',
    'connected_as' => 'Veritabanına bağlı olarak',
    'failed_connection' => 'Veritabanına bağlanılamadı.',
];
