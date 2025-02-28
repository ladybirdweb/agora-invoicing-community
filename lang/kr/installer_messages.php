<?php

return [

    'title' => 'Agora 인보이싱 설치 프로그램',
    'probe' => 'Agora 인보이싱 프로브',
    'magic_phrase' => '마법의 문구는 무엇인가요',
    'server_requirements' => '서버 요구 사항',
    'database_setup' => '데이터베이스 설정',
    'getting_started' => '시작하기',
    'final' => '최종',
    'directory' => '디렉토리',
    'permissions' => '권한',
    'requisites' => '필수 사항',
    'status' => '상태',
    'php_extensions' => 'PHP 확장',
    'not_enabled' => '비활성화됨',
    'extension_not_enabled' => '비활성화됨: 이를 활성화하려면 서버에 확장을 설치하고 :php_ini_file을 업데이트하여 :extensionName을 활성화하세요. <a href=":url" target="_blank">서버에서 PHP 확장 설치 방법</a>',
    'mod_rewrite' => 'Mod Rewrite',
    'off_apache' => 'OFF (Apache를 사용하는 경우 <var><strong>AllowOverride</strong></var>가 apache 구성에서 <var><strong>All</strong></var>로 설정되어 있는지 확인하세요)',
    'rewrite_engine' => 'Rewrite Engine',
    'user_url' => '사용자 친화적인 URL',

    'host' => '호스트',
    'host_tooltip' => 'MySQL이 Agora 인보이싱과 동일한 서버에 설치되어 있으면 localhost로 설정하세요.',
    'database_name_label' => '데이터베이스 이름',
    'mysql_port_label' => 'MySQL 포트 번호',
    'mysql_port_tooltip' => 'MySQL 서버가 수신 대기 중인 포트 번호. 기본값은 3306입니다.',
    'username' => '사용자 이름',
    'password_label' => '비밀번호',
    'test_prerequisites_message' => '이 테스트는 Agora 인보이싱 설치에 필요한 필수 사항을 확인합니다.',
    'previous' => '이전',

    'sign_up_as_admin' => '관리자로 가입',
    'first_name' => '이름',
    'first_name_required' => '이름은 필수입니다.',
    'last_name' => '성',
    'last_name_required' => '성은 필수입니다.',
    'username_info' => '사용자 이름은 알파벳, 숫자, 공백, 밑줄, 하이픈, 마침표 및 @ 기호만 포함할 수 있습니다.',
    'email' => '이메일',
    'email_required' => '사용자 이메일은 필수입니다.',
    'password_required' => '비밀번호는 필수입니다.',
    'confirm_password' => '비밀번호 확인',
    'confirm_password_required' => '비밀번호 확인은 필수입니다.',
    'password_requirements' => '비밀번호는 다음 조건을 충족해야 합니다:',
    'password_requirements_list' => [
        ['id' => 'length', 'text' => '8~16자 사이'],
        ['id' => 'letter', 'text' => '소문자 (a-z)'],
        ['id' => 'capital', 'text' => '대문자 (A-Z)'],
        ['id' => 'number', 'text' => '숫자 (0-9)'],
        ['id' => 'space', 'text' => '특수 문자 (~*!@$#%_+.?:,{ })'],
    ],

    // 시스템 정보
    'system_information' => '시스템 정보',
    'environment' => '환경',
    'environment_required' => '환경은 필수입니다.',
    'production' => '프로덕션',
    'development' => '개발',
    'testing' => '테스트',
    'cache_driver' => '캐시 드라이버',
    'cache_driver_required' => '캐시 드라이버는 필수입니다.',
    'file' => '파일',
    'redis' => 'Redis',
    'password' => '비밀번호',

    // Redis 설정
    'redis_setup' => 'Redis 설정',
    'redis_host' => 'Redis 호스트',
    'redis_port' => 'Redis 포트',
    'redis_password' => 'Redis 비밀번호',

    // 버튼
    'continue' => '계속',

    // 최종 설정
    'final_setup' => 'Agora 인보이싱 애플리케이션이 준비되었습니다!',
    'installation_complete' => '잘했어요! 설치를 완료했습니다.',

    // 더 알아보기
    'learn_more' => '더 알아보기',
    'knowledge_base' => '지식 베이스',
    'email_support' => '이메일 지원',

    // 다음 단계
    'next_step' => '다음 단계',
    'login_button' => 'Agora 인보이싱에 로그인',

    'pre_migration_success' => '미리 마이그레이션이 성공적으로 테스트되었습니다.',
    'migrating_tables' => '데이터베이스에서 테이블 마이그레이션 중',
    'db_connection_error' => '데이터베이스 연결이 업데이트되지 않았습니다.',
    'database_setup_success' => '데이터베이스 설정이 성공적으로 완료되었습니다.',
    'env_file_created' => '환경 구성 파일이 성공적으로 생성되었습니다.',
    'pre_migration_test' => '미리 마이그레이션 테스트 실행 중',

    'redis_host_required' => 'Redis 호스트는 필수입니다.',
    'redis_password_required' => 'Redis 비밀번호는 필수입니다.',
    'redis_port_required' => 'Redis 포트는 필수입니다.',
    'password_regex' => '비밀번호는 최소 8자, 하나의 대문자, 하나의 소문자, 하나의 숫자, 하나의 특수 문자를 포함해야 합니다.',
    'setup_completed' => '설치가 성공적으로 완료되었습니다!',

    'database' => '데이터베이스',
    'selected' => '선택됨',
    'mysql_version_is' => 'MySQL 버전은',
    'database_empty' => '데이터베이스가 비어 있습니다.',
    'database_not_empty' => 'Agora 인보이싱 설치를 위해서는 빈 데이터베이스가 필요합니다. 데이터베이스에 이미 테이블과 데이터가 있습니다.',
    'mysql_version_required' => 'MySQL 5.6 이상 또는 MariaDB 10.3 이상으로 업그레이드하는 것이 좋습니다!',
    'database_connection_unsuccessful' => '데이터베이스 연결에 실패했습니다.',
    'connected_as' => '데이터베이스에 연결됨',
    'failed_connection' => '데이터베이스에 연결하지 못했습니다.',
    'magic_phrase_not_work' => '입력한 마법의 문구가 작동하지 않습니다.',
    'magic_required' => '마법의 문구는 필수입니다.',
    'user_name_regex' => '사용자 이름은 3~20자여야 하며, 알파벳, 숫자, 공백, 밑줄, 하이픈, 마침표 및 @ 기호만 포함할 수 있습니다.',

];
