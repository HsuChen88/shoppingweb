<?php

return [
    'name' => 'ShawningShop 鍵盤世界',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => ($_ENV['APP_DEBUG'] ?? 'false') === 'true',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'Asia/Taipei',
    'locale' => 'zh_TW',
    'session' => [
        'lifetime' => $_ENV['SESSION_LIFETIME'] ?? 86400, // 24 hours
        'cookie_name' => 'user_id_cookie',
        'cookie_lifetime' => 86400 * 7, // 7 days
    ]
];

