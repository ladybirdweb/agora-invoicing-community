<?php

return [

    'title' => 'Cài đặt Agora Invoicing',
    'probe' => 'Kiểm tra Agora Invoicing',
    'magic_phrase' => 'Câu thần chú là gì',
    'server_requirements' => 'Yêu cầu máy chủ',
    'database_setup' => 'Cài đặt cơ sở dữ liệu',
    'getting_started' => 'Bắt đầu',
    'final' => 'Cuối cùng',
    'directory' => 'Thư mục',
    'permissions' => 'Quyền hạn',
    'requisites' => 'Yêu cầu',
    'status' => 'Trạng thái',
    'php_extensions' => 'Tiện ích mở rộng PHP',
    'not_enabled' => 'Chưa kích hoạt',
    'extension_not_enabled' => 'Chưa kích hoạt: Để kích hoạt, vui lòng cài đặt tiện ích mở rộng trên máy chủ của bạn và cập nhật :php_ini_file để bật :extensionName. <a href=":url" target="_blank">Cách cài đặt tiện ích mở rộng PHP trên máy chủ của tôi?</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'TẮT (Nếu bạn sử dụng apache, hãy đảm bảo <var><strong>AllowOverride</strong></var> được thiết lập là <var><strong>All</strong></var> trong cấu hình apache)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => 'URL thân thiện với người dùng',

    'host' => 'Máy chủ',
    'host_tooltip' => 'Nếu MySQL của bạn được cài đặt trên cùng một máy chủ với Agora Invoicing, hãy để là localhost',
    'database_name_label' => 'Tên cơ sở dữ liệu',
    'mysql_port_label' => 'Số cổng MySQL',
    'mysql_port_tooltip' => 'Số cổng mà máy chủ MySQL của bạn đang lắng nghe. Mặc định là 3306',
    'username' => 'Tên người dùng',
    'password_label' => 'Mật khẩu',
    'test_prerequisites_message' => 'Bài kiểm tra này sẽ kiểm tra các yêu cầu cần thiết để cài đặt Agora Invoicing',
    'previous' => 'Trước',

    'sign_up_as_admin' => 'Đăng ký làm Quản trị viên',
    'first_name' => 'Tên',
    'first_name_required' => 'Tên là bắt buộc',
    'last_name' => 'Họ',
    'last_name_required' => 'Họ là bắt buộc',
    'username_info' => 'Tên người dùng chỉ có thể chứa ký tự chữ và số, khoảng trắng, dấu gạch dưới, dấu gạch nối, dấu chấm và ký tự @.',
    'email' => 'Email',
    'email_required' => 'Email người dùng là bắt buộc',
    'password_required' => 'Mật khẩu là bắt buộc',
    'confirm_password' => 'Xác nhận mật khẩu',
    'confirm_password_required' => 'Xác nhận mật khẩu là bắt buộc',
    'password_requirements' => 'Mật khẩu của bạn phải có:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => 'Giữa 8-16 ký tự'],
        ['id' => 'letter', 'text' => 'Ký tự viết thường (a-z)'],
        ['id' => 'capital', 'text' => 'Ký tự viết hoa (A-Z)'],
        ['id' => 'number', 'text' => 'Số (0-9)'],
        ['id' => 'space', 'text' => 'Ký tự đặc biệt (~*!@$#%_+.?:,{ })'],
    ],

    // Thông tin hệ thống
    'system_information' => 'Thông tin hệ thống',
    'environment' => 'Môi trường',
    'environment_required' => 'Môi trường là bắt buộc',
    'production' => 'Sản xuất',
    'development' => 'Phát triển',
    'testing' => 'Kiểm tra',
    'cache_driver' => 'Trình điều khiển bộ nhớ đệm',
    'cache_driver_required' => 'Trình điều khiển bộ nhớ đệm là bắt buộc',
    'file' => 'Tập tin',
    'redis' => 'Redis',
    'password' => 'Mật khẩu',

    // Cài đặt Redis
    'redis_setup' => 'Cài đặt Redis',
    'redis_host' => 'Máy chủ Redis',
    'redis_port' => 'Cổng Redis',
    'redis_password' => 'Mật khẩu Redis',

    // Nút
    'continue' => 'Tiếp tục',

    // Cài đặt cuối cùng
    'final_setup' => 'Ứng dụng Agora Invoicing của bạn đã sẵn sàng!',
    'installation_complete' => 'Tất cả đã hoàn tất, bạn đã hoàn thành cài đặt.',

    // Tìm hiểu thêm
    'learn_more' => 'Tìm hiểu thêm',
    'knowledge_base' => 'Cơ sở kiến thức',
    'email_support' => 'Hỗ trợ qua email',

    // Bước tiếp theo
    'next_step' => 'Bước tiếp theo',
    'login_button' => 'Đăng nhập vào Agora Invoicing',

    'pre_migration_success' => 'Kiểm tra trước khi di chuyển đã thành công',
    'migrating_tables' => 'Đang di chuyển các bảng trong cơ sở dữ liệu',
    'db_connection_error' => 'Không thể cập nhật kết nối cơ sở dữ liệu.',
    'database_setup_success' => 'Cơ sở dữ liệu đã được cài đặt thành công.',
    'env_file_created' => 'Tệp cấu hình môi trường đã được tạo thành công',
    'pre_migration_test' => 'Đang chạy bài kiểm tra trước khi di chuyển',

    'redis_host_required' => 'Máy chủ Redis là bắt buộc.',
    'redis_password_required' => 'Mật khẩu Redis là bắt buộc.',
    'redis_port_required' => 'Cổng Redis là bắt buộc.',
    'password_regex' => 'Mật khẩu phải có ít nhất 8 ký tự, một ký tự viết hoa, một ký tự viết thường, một số và một ký tự đặc biệt.',
    'setup_completed' => 'Cài đặt hoàn tất thành công!',

    'database' => 'Cơ sở dữ liệu',
    'selected' => 'Đã chọn',
    'mysql_version_is' => 'Phiên bản MySQL là',
    'database_empty' => 'Cơ sở dữ liệu trống',
    'database_not_empty' => 'Cài đặt Agora Invoicing yêu cầu cơ sở dữ liệu trống, cơ sở dữ liệu của bạn đã có bảng và dữ liệu.',
    'mysql_version_required' => 'Chúng tôi khuyên bạn nên nâng cấp lên MySQL 5.6 hoặc MariaDB 10.3!',
    'database_connection_unsuccessful' => 'Kết nối cơ sở dữ liệu không thành công.',
    'connected_as' => 'Đã kết nối với cơ sở dữ liệu với tư cách',
    'failed_connection' => 'Kết nối cơ sở dữ liệu thất bại.',
    'magic_phrase_not_work' => 'Câu thần chú bạn nhập không hoạt động.',
    'magic_required' => 'Câu thần chú là bắt buộc.',
    'user_name_regex' => 'Tên người dùng phải từ 3-20 ký tự và chỉ có thể chứa chữ cái, số, khoảng trắng, dấu gạch dưới, dấu gạch nối, dấu chấm và ký tự @.',

];
