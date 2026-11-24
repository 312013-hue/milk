<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('yonghe_admin');
    session_start();
}

require_once __DIR__ . '/csrf.php';

function is_logged_in(): bool
{
    if (empty($_SESSION['admin_id'])) {
        return false;
    }
    // 閒置過久自動登出
    $idle = config()['session_idle'];
    if (isset($_SESSION['last_active']) && time() - $_SESSION['last_active'] > $idle) {
        logout_admin();
        return false;
    }
    $_SESSION['last_active'] = time();
    return true;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login_admin(int $id, string $username): void
{
    session_regenerate_id(true);          // 防止 session fixation
    $_SESSION['admin_id']    = $id;
    $_SESSION['admin_name']  = $username;
    $_SESSION['last_active'] = time();
}

function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
