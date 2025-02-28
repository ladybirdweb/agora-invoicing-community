<?php

return [

    'title' => 'Pemasang Faktur Agora',
    'probe' => 'Probe Faktur Agora',
    'magic_phrase' => 'Apa frase ajaib',
    'server_requirements' => 'Persyaratan Server',
    'database_setup' => 'Pengaturan Database',
    'getting_started' => 'Memulai',
    'final' => 'Final',
    'directory' => 'Direktori',
    'permissions' => 'Izin',
    'requisites' => 'Persyaratan',
    'status' => 'Status',
    'php_extensions' => 'Ekstensi PHP',
    'not_enabled' => 'Tidak Diaktifkan',
    'extension_not_enabled' => 'Tidak Diaktifkan: Untuk mengaktifkan ini, harap pasang ekstensi di server Anda dan perbarui :php_ini_file untuk mengaktifkan :extensionName. <a href=":url" target="_blank">Cara memasang ekstensi PHP di server saya?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'MATI (Jika Anda menggunakan apache, pastikan <var><strong>AllowOverride</strong></var> diatur ke <var><strong>All</strong></var> dalam konfigurasi apache)',
    'rewrite_engine' => 'Mesin Rewrite',
    'user_url' => 'URL yang ramah pengguna',

    'host' => 'Host',
    'host_tooltip' => 'Jika MySQL Anda terpasang di server yang sama dengan Agora Invoicing, biarkan localhost',
    'database_name_label' => 'Nama database',
    'mysql_port_label' => 'Nomor port MySQL',
    'mysql_port_tooltip' => 'Nomor port yang digunakan server MySQL Anda. Secara default, itu adalah 3306',
    'username' => 'Nama Pengguna',
    'password_label' => 'Kata Sandi',
    'test_prerequisites_message' => 'Tes ini akan memeriksa persyaratan yang diperlukan untuk menginstal Agora Invoicing',
    'previous' => 'Sebelumnya',

    'sign_up_as_admin' => 'Daftar sebagai Admin',
    'first_name' => 'Nama Depan',
    'first_name_required' => 'Nama Depan wajib diisi',
    'last_name' => 'Nama Belakang',
    'last_name_required' => 'Nama Belakang wajib diisi',
    'username_info' => 'Nama pengguna hanya boleh mengandung karakter alfanumerik, spasi, garis bawah, tanda hubung, titik, dan simbol @.',
    'email' => 'Email',
    'email_required' => 'Email pengguna wajib diisi',
    'password_required' => 'Kata sandi wajib diisi',
    'confirm_password' => 'Konfirmasi Kata Sandi',
    'confirm_password_required' => 'Konfirmasi Kata Sandi wajib diisi',
    'password_requirements' => 'Kata sandi Anda harus memiliki:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Antara 8-16 karakter'],
        ['id' => 'letter', 'text' => 'Karakter huruf kecil (a-z)'],
        ['id' => 'capital', 'text' => 'Karakter huruf kapital (A-Z)'],
        ['id' => 'number', 'text' => 'Angka (0-9)'],
        ['id' => 'space', 'text' => 'Karakter khusus (~*!@$#%_+.?:,{ })'],
    ],

    // Informasi Sistem
    'system_information' => 'Informasi Sistem',
    'environment' => 'Lingkungan',
    'environment_required' => 'Lingkungan wajib diisi',
    'production' => 'Produksi',
    'development' => 'Pengembangan',
    'testing' => 'Pengujian',
    'cache_driver' => 'Driver Cache',
    'cache_driver_required' => 'Driver Cache wajib diisi',
    'file' => 'File',
    'redis' => 'Redis',
    'password' => 'Kata Sandi',

    // Pengaturan Redis
    'redis_setup' => 'Pengaturan Redis',
    'redis_host' => 'Host Redis',
    'redis_port' => 'Port Redis',
    'redis_password' => 'Kata Sandi Redis',

    // Tombol
    'continue' => 'Lanjutkan',

    // Pengaturan Akhir
    'final_setup' => 'Aplikasi Agora Invoicing Anda Siap!',
    'installation_complete' => 'Baiklah, sparky! Anda telah berhasil melalui proses instalasi.',

    // Pelajari Lebih Lanjut
    'learn_more' => 'Pelajari Lebih Lanjut',
    'knowledge_base' => 'Pangkalan Pengetahuan',
    'email_support' => 'Dukungan Email',

    // Langkah Selanjutnya
    'next_step' => 'Langkah Selanjutnya',
    'login_button' => 'Masuk ke Agora Invoicing',

    'pre_migration_success' => 'Pralalihan telah diuji dengan sukses',
    'migrating_tables' => 'Migrasi tabel di database',
    'db_connection_error' => 'Koneksi database tidak diperbarui.',
    'database_setup_success' => 'Database telah berhasil disiapkan.',
    'env_file_created' => 'File konfigurasi lingkungan telah berhasil dibuat',
    'pre_migration_test' => 'Menjalankan uji pra-migrasi',

    'redis_host_required' => 'Host Redis wajib diisi.',
    'redis_password_required' => 'Kata Sandi Redis wajib diisi.',
    'redis_port_required' => 'Port Redis wajib diisi.',
    'password_regex' => 'Kata sandi harus mengandung setidaknya 8 karakter, satu huruf kapital, satu huruf kecil, satu angka, dan satu karakter khusus.',
    'setup_completed' => 'Pengaturan berhasil diselesaikan!',

    'database' => 'Database',
    'selected' => 'Terpilih',
    'mysql_version_is' => 'Versi MySQL adalah',
    'database_empty' => 'Database kosong',
    'database_not_empty' => 'Instalasi Agora Invoicing memerlukan database kosong, database Anda sudah memiliki tabel dan data di dalamnya.',
    'mysql_version_required' => 'Kami menyarankan untuk memperbarui ke MySQL 5.6 atau MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Koneksi database tidak berhasil.',
    'connected_as' => 'Terhubung ke database sebagai',
    'failed_connection' => 'Gagal terhubung ke database.',
    'magic_phrase_not_work' => 'Frase ajaib yang Anda masukkan tidak berfungsi.',
    'magic_required' => 'Frase ajaib wajib diisi.',
    'user_name_regex' => 'Nama pengguna harus 3-20 karakter dan hanya boleh mengandung huruf, angka, spasi, garis bawah, tanda hubung, titik dan simbol @.',

];

