<?php

function checkArray($key, $array)
{
    $value = '';
    if (is_array($array) && array_key_exists($key, $array)) {
        $value = $array[$key];
    }

    return $value;
}

function mime($type)
{
    if ($type == 'jpg' ||
            $type == 'png' ||
            $type == 'jpeg' ||
            $type == 'gif' 
            starts_with($type, 'image')) {
        return 'image';
    }
}

function isInstall()
{
    $check = false;
    $env = base_path('.env');
    if (\File::exists($env) && env('DB_INSTALL') == 1) {
        $check = true;
    }

    return $check;
}

function agoratime($date, $hour = 0, $min = 0, $sec = 0, $tz = '')
{
    if (is_bool($hour) && $hour === true) {
        $hour = $date->hour;
    }
    if (is_bool($min) && $min === true) {
        $min = $date->minute;
    }
    if (is_bool($sec) && $sec === true) {
        $sec = $date->second;
    }
    if (!$tz) {
        $tz = /* @scrutinizer ignore-call */ timezone();
    }
    $date1 = \Carbon\Carbon::create($date->year, $date->month, $date->day, $hour, $min, $sec, $tz);

    return $date1->hour($hour)->minute($min)->second($sec);
}
