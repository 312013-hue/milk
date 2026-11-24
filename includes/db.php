<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Taipei');

function config(): array
{
    static $cfg = null;
    return $cfg ??= require __DIR__ . '/config.php';
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $cfg = config();
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    if ($cfg['db_driver'] === 'sqlite') {
        $pdo = new PDO('sqlite:' . $cfg['sqlite_path'], null, null, $options);
        $pdo->exec('PRAGMA foreign_keys = ON');
    } else {
        $m   = $cfg['mysql'];
        $dsn = "mysql:host={$m['host']};port={$m['port']};dbname={$m['name']};charset={$m['charset']}";
        // 讓 MySQL 的 NOW()、CURDATE() 也使用台灣時間
        $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET time_zone = '+08:00'";
        $pdo = new PDO($dsn, $m['user'], $m['pass'], $options);
    }
    return $pdo;
}

function e(mixed $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
