<?php

function getEnvVar($key, $default = null)
{
    $val = getenv($key);
    return ($val !== false) ? $val : $default;
}

// Render config
if (getEnvVar('DB_HOST')) {
    return [
        'host' => getEnvVar('DB_HOST'),
        'user' => getEnvVar('DB_USER'),
        'pass' => getEnvVar('DB_PASS'),
        'name' => getEnvVar('DB_NAME'),
        'port' => getEnvVar('DB_PORT', 3306)
    ];
} else {
    // LOCAL CONFIG (XAMPP defaults)
    return [ #DEFAULTS
        'host' => 'localhost', #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'user' => 'root', #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'pass' => '',    #PAG NI-UPDATE MO TO, WAG MO NANG IPUSH
        'name' => 'slubazaar',
        'port' => 3306
    ];
}