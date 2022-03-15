<?php

require_once __DIR__.'/../vendor/autoload.php';

if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        return 'test-token';
    }
}
