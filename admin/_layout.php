<?php
declare(strict_types=1);
// 後台共用版型：每個後台頁面開頭 require 這個檔案即可（會自動檢查登入）
require_once __DIR__ . '/../includes/auth.php';
require_login();

const ORDER_STATUS = [
    'pending'   => '待處理',
    'confirmed' => '已確認',
    'shipped'   => '已出貨',
    'completed' => '已完成',
    'cancelled' => '已取消',
];

function money(mixed $n): string
{
    return 'NT$ ' . number_format((int)$n);
}

function flash_set(string $type, string $msg): void
{
    $_SESSION['flash'] = [$type, $msg];
}

function img_url(string $img): string
{
    return $img === '' ? '' : '../' . $img;
}

function admin_header(string $title, string $active): void
{
    $nav = [
        'dashboard' => ['index.php',    '📊', '儀表板'],
        'products'  => ['products.php', '📦', '商品管理'],
        'orders'    => ['orders.php',   '🧾', '訂單管理'],
        'admins'    => ['admins.php',   '👤', '管理員'],
    ];
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title) ?>｜永和豆漿後台</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
<aside class="side">
  <div class="brand">永和豆漿<small>後台管理</small></div>
  <nav>
    <?php foreach ($nav as $key => [$href, $icon, $label]): ?>
      <a href="<?= $href ?>" class="<?= $key === $active ? 'on' : '' ?>"><?= $icon ?> <?= $label ?></a>
    <?php endforeach; ?>
    <a href="../index.php" target="_blank">🏠 查看前台</a>
  </nav>
  <div class="side-foot">
    <div class="who">👤 <?= e($_SESSION['admin_name'] ?? '') ?></div>
    <form method="post" action="logout.php"><?= csrf_field() ?><button type="submit" class="btn ghost">登出</button></form>
  </div>
</aside>
<main class="main">
  <h1 class="page-title"><?= e($title) ?></h1>
  <?php if ($flash): ?>
    <div class="flash <?= $flash[0] === 'ok' ? 'ok' : 'err' ?>"><?= e($flash[1]) ?></div>
  <?php endif; ?>
<?php
}

function admin_footer(): void
{
    echo "</main>\n</body>\n</html>";
}
