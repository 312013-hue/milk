<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';

// 只接受 POST + CSRF，避免別人用一張圖片就把你登出
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    logout_admin();
    header('Location: login.php?out=1');
    exit;
}

header('Location: index.php');
exit;
