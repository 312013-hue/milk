<?php
declare(strict_types=1);

// 資料庫設定：driver 填 'mysql' 或 'sqlite'
return [
    'db_driver' => 'mysql',

    'mysql' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'yonghe',      // 請先在 phpMyAdmin 建立這個資料庫（編碼 utf8mb4）
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    'sqlite_path' => __DIR__ . '/../data/yonghe.sqlite',

    // 後台閒置多久自動登出（秒）
    'session_idle' => 7200,
];
