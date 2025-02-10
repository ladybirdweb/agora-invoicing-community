<?php
return [

    'accepted' => ':attribute는 반드시 동의해야 합니다.',
    'accepted_if' => ':attribute는 :other가 :value일 때 동의해야 합니다.',
    'active_url' => ':attribute는 유효한 URL이 아닙니다.',
    'after' => ':attribute는 :date 이후의 날짜여야 합니다.',
    'after_or_equal' => ':attribute는 :date 이후 또는 같은 날짜여야 합니다.',
    'alpha' => ':attribute는 문자만 포함해야 합니다.',
    'alpha_dash' => ':attribute는 문자, 숫자, 대시 및 밑줄만 포함해야 합니다.',
    'alpha_num' => ':attribute는 문자와 숫자만 포함해야 합니다.',
    'array' => ':attribute는 배열이어야 합니다.',
    'before' => ':attribute는 :date 이전의 날짜여야 합니다.',
    'before_or_equal' => ':attribute는 :date 이전 또는 같은 날짜여야 합니다.',
    'between' => [
        'array' => ':attribute는 :min과 :max 항목 사이여야 합니다.',
        'file' => ':attribute는 :min과 :max 킬로바이트 사이여야 합니다.',
        'numeric' => ':attribute는 :min과 :max 사이여야 합니다.',
        'string' => ':attribute는 :min과 :max 자 사이여야 합니다.',
    ],
    'boolean' => ':attribute 필드는 true 또는 false여야 합니다.',
    'confirmed' => ':attribute 확인이 일치하지 않습니다.',
    'current_password' => '비밀번호가 올바르지 않습니다.',
    'date' => ':attribute는 유효한 날짜가 아닙니다.',
    'date_equals' => ':attribute는 :date와 동일한 날짜여야 합니다.',
    'date_format' => ':attribute는 :format 형식과 일치하지 않습니다.',
    'declined' => ':attribute는 거부되어야 합니다.',
    'declined_if' => ':attribute는 :other가 :value일 때 거부되어야 합니다.',
    'different' => ':attribute와 :other는 달라야 합니다.',
    'digits' => ':attribute는 :digits 자여야 합니다.',
    'digits_between' => ':attribute는 :min과 :max 자 사이여야 합니다.',
    'dimensions' => ':attribute는 유효하지 않은 이미지 크기를 가집니다.',
    'distinct' => ':attribute 필드는 중복 값이 있습니다.',
    'doesnt_start_with' => ':attribute는 다음 중 하나로 시작할 수 없습니다: :values.',
    'email' => ':attribute는 유효한 이메일 주소여야 합니다.',
    'ends_with' => ':attribute는 다음 중 하나로 끝나야 합니다: :values.',
    'enum' => '선택된 :attribute는 유효하지 않습니다.',
    'exists' => '선택된 :attribute는 유효하지 않습니다.',
    'file' => ':attribute는 파일이어야 합니다.',
    'filled' => ':attribute 필드는 값이 있어야 합니다.',
    'gt' => [
        'array' => ':attribute는 :value 항목보다 많아야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 커야 합니다.',
        'numeric' => ':attribute는 :value보다 커야 합니다.',
        'string' => ':attribute는 :value 자보다 길어야 합니다.',
    ],
    'gte' => [
        'array' => ':attribute는 :value 항목 이상이어야 합니다.',
        'file' => ':attribute는 :value 킬로바이트 이상이어야 합니다.',
        'numeric' => ':attribute는 :value 이상이어야 합니다.',
        'string' => ':attribute는 :value 자 이상이어야 합니다.',
    ],
    'image' => ':attribute는 이미지여야 합니다.',
    'in' => '선택된 :attribute는 유효하지 않습니다.',
    'in_array' => ':attribute 필드는 :other에 존재하지 않습니다.',
    'integer' => ':attribute는 정수여야 합니다.',
    'ip' => ':attribute는 유효한 IP 주소여야 합니다.',
    'ipv4' => ':attribute는 유효한 IPv4 주소여야 합니다.',
    'ipv6' => ':attribute는 유효한 IPv6 주소여야 합니다.',
    'json' => ':attribute는 유효한 JSON 문자열이어야 합니다.',
    'lt' => [
        'array' => ':attribute는 :value 항목보다 적어야 합니다.',
        'file' => ':attribute는 :value 킬로바이트보다 작아야 합니다.',
        'numeric' => ':attribute는 :value보다 작아야 합니다.',
        'string' => ':attribute는 :value 자보다 짧아야 합니다.',
    ],
    'lte' => [
        'array' => ':attribute는 :value 항목 이하이어야 합니다.',
        'file' => ':attribute는 :value 킬로바이트 이하이어야 합니다.',
        'numeric' => ':attribute는 :value 이하이어야 합니다.',
        'string' => ':attribute는 :value 자 이하이어야 합니다.',
    ],
    'mac_address' => ':attribute는 유효한 MAC 주소여야 합니다.',
    'max' => [
        'array' => ':attribute는 :max 항목 이하이어야 합니다.',
        'file' => ':attribute는 :max 킬로바이트 이하이어야 합니다.',
        'numeric' => ':attribute는 :max 이하이어야 합니다.',
        'string' => ':attribute는 :max 자 이하이어야 합니다.',
    ],
    'mimes' => ':attribute는 다음 형식의 파일이어야 합니다: :values.',
    'mimetypes' => ':attribute는 다음 형식의 파일이어야 합니다: :values.',
    'min' => [
        'array' => ':attribute는 최소 :min 항목이어야 합니다.',
        'file' => ':attribute는 최소 :min 킬로바이트이어야 합니다.',
        'numeric' => ':attribute는 최소 :min이어야 합니다.',
        'string' => ':attribute는 최소 :min 자이어야 합니다.',
    ],
    'multiple_of' => ':attribute는 :value의 배수여야 합니다.',
    'not_in' => '선택된 :attribute는 유효하지 않습니다.',
    'not_regex' => ':attribute 형식이 유효하지 않습니다.',
    'numeric' => ':attribute는 숫자여야 합니다.',
    'password' => [
        'letters' => ':attribute는 최소한 하나의 문자를 포함해야 합니다.',
        'mixed' => ':attribute는 최소한 하나의 대문자와 소문자를 포함해야 합니다.',
        'numbers' => ':attribute는 최소한 하나의 숫자를 포함해야 합니다.',
        'symbols' => ':attribute는 최소한 하나의 기호를 포함해야 합니다.',
        'uncompromised' => '주어진 :attribute는 데이터 유출에서 발견되었습니다. 다른 :attribute를 선택하세요.',
    ],
    'present' => ':attribute 필드는 존재해야 합니다.',
    'prohibited' => ':attribute 필드는 금지됩니다.',
    'prohibited_if' => ':attribute 필드는 :other가 :value일 때 금지됩니다.',
    'prohibited_unless' => ':attribute 필드는 :other가 :values에 있을 때만 금지되지 않습니다.',
    'prohibits' => ':attribute 필드는 :other가 존재하는 것을 금지합니다.',
    'regex' => ':attribute 형식이 유효하지 않습니다.',
    'required' => ':attribute 필드는 필수입니다.',
    'required_array_keys' => ':attribute 필드는 :values에 대한 항목을 포함해야 합니다.',
    'required_if' => ':attribute 필드는 :other가 :value일 때 필수입니다.',
    'required_unless' => ':attribute 필드는 :other가 :values에 있을 때만 필수입니다.',
    'required_with' => ':values가 있을 때 :attribute 필드는 필수입니다.',
    'required_with_all' => ':values가 모두 있을 때 :attribute 필드는 필수입니다.',
    'required_without' => ':values가 없을 때 :attribute 필드는 필수입니다.',
    'required_without_all' => ':values가 모두 없을 때 :attribute 필드는 필수입니다.',
    'same' => ':attribute와 :other는 일치해야 합니다.',
    'size' => [
        'array' => ':attribute는 :size 항목을 포함해야 합니다.',
        'file' => ':attribute는 :size 킬로바이트여야 합니다.',
        'numeric' => ':attribute는 :size이어야 합니다.',
        'string' => ':attribute는 :size 자여야 합니다.',
    ],
    'starts_with' => ':attribute는 다음 중 하나로 시작해야 합니다: :values.',
    'string' => ':attribute는 문자열이어야 합니다.',
    'timezone' => ':attribute는 유효한 시간대여야 합니다.',
    'unique' => ':attribute는 이미 사용되었습니다.',
    'uploaded' => ':attribute 업로드에 실패했습니다.',
    'url' => ':attribute는 유효한 URL이어야 합니다.',
    'uuid' => ':attribute는 유효한 UUID여야 합니다.',

];
