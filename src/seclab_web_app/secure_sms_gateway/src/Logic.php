<?php
function parse_sms_text($text) {
    $parts = explode(';', $text);
    $data = [];
    foreach ($parts as $part) {
        if (strpos($part, '=') !== false) {
            list($key, $val) = explode('=', $part, 2);
            $data[trim(strtolower($key))] = trim($val);
        }
    }
    return $data;
}

function determine_priority($message) {
    $msg = strtolower($message);
    if (preg_match('/urgent|immediate|now/', $msg)) {
        return 'high';
    } elseif (preg_match('/later|tomorrow/', $msg)) {
        return 'low';
    }
    return 'normal';
}

