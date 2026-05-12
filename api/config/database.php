<?php
$storageUrl = getenv('STORAGE_URL');

if ($storageUrl) {
    $parsed = parse_url($storageUrl);
    return [
        'host'     => $parsed['host'] ?? 'localhost',
        'port'     => $parsed['port'] ?? '5432',
        'dbname'   => isset($parsed['path']) ? ltrim($parsed['path'], '/') : 'barangay_db',
        'user'     => $parsed['user'] ?? 'postgres',
        'password' => $parsed['pass'] ?? ''
    ];
}

return [
    'host'     => getenv('DB_HOST')     ?: 'localhost',
    'port'     => getenv('DB_PORT')     ?: '5432',
    'dbname'   => getenv('DB_NAME')     ?: 'barangay_db',
    'user'     => getenv('DB_USER')     ?: 'postgres',
    'password' => getenv('DB_PASSWORD') ?: 'jazzpogi'
];
