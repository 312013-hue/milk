<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

const MAX_FAILS   = 5;     // 連續失敗幾次後鎖定
const LOCK_SECONDS = 300;  // 鎖定秒數

$error = '';
$notice = isset($_GET['out']) ? '您已成功登出。' : (isset($_GET['timeout']) ? '閒置過久，請重新登入。' : '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lockedUntil = $_SESSION['lock_until'] ?? 0;

    if (!csrf_verify()) {
        $error = '頁面已過期，請重新整理後再試。';
    } elseif ($lockedUntil > time()) {
        $error = '嘗試次數過多，請 ' . ceil(($lockedUntil - time()) / 60) . ' 分鐘後再試。';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $stmt = db()->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // 帳號不存在時也跑一次 password_verify，避免從回應時間猜出帳號是否存在
        $hash = $admin['password_hash'] ?? '$2y$10$usesomesillystringforsaltandthenhash0123456789abcdefghijk';
        $ok   = password_verify($password, $hash) && $admin;

        if ($ok) {
            unset($_SESSION['fails'], $_SESSION['lock_until']);
            if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
                db()->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
                    ->execute([password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
            }
            db()->prepare('UPDATE admins SET last_login = ? WHERE id = ?')
                ->execute([date('Y-m-d H:i:s'), $admin['id']]);
            login_admin((int)$admin['id'], $admin['username']);
            header('Location: index.php');
            exit;
        }

        $_SESSION['fails'] = ($_SESSION['fails'] ?? 0) + 1;
        if ($_SESSION['fails'] >= MAX_FAILS) {
            $_SESSION['lock_until'] = time() + LOCK_SECONDS;
            $_SESSION['fails'] = 0;
            $error = '嘗試次數過多，請 5 分鐘後再試。';
        } else {
            $error = '帳號或密碼錯誤。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex,nofollow">
<title>後台登入｜永和豆漿</title>
<style>
  :root { --brown:#7a5c47; --light-brown:#c9ad92; --bg:#f9f6ee; --text:#444; }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    min-height: 100vh; display: grid; place-items: center; padding: 20px;
    background: var(--bg); color: var(--text);
    font-family: "Noto Sans TC", "Microsoft JhengHei", sans-serif;
  }
  .box {
    width: 100%; max-width: 400px; background: #fff; border-radius: 20px;
    padding: 40px 32px; box-shadow: 0 8px 28px rgba(0,0,0,.08);
  }
  .box img { display: block; height: 90px; margin: 0 auto 12px; }
  h1 { text-align: center; font-size: 26px; font-weight: normal; color: var(--brown); margin-bottom: 4px; }
  .sub { text-align: center; font-size: 15px; opacity: .6; margin-bottom: 24px; }
  label { display: block; font-size: 15px; margin: 14px 0 6px; color: var(--brown); }
  input[type=text], input[type=password] {
    width: 100%; padding: 12px 14px; font-size: 17px; font-family: inherit;
    border: 1px solid var(--light-brown); border-radius: 10px; outline: none;
  }
  input:focus { border-color: var(--brown); box-shadow: 0 0 0 3px rgba(122,92,71,.15); }
  button {
    width: 100%; margin-top: 24px; padding: 13px; font-size: 18px; font-family: inherit;
    color: #fff; background: var(--brown); border: 0; border-radius: 999px; cursor: pointer;
    transition: background .2s;
  }
  button:hover { background: #5f4837; }
  .msg { padding: 10px 14px; border-radius: 10px; font-size: 15px; margin-bottom: 4px; }
  .err { background: #fdecea; color: #a8321f; }
  .ok  { background: #edf5ea; color: #4a6b3a; }
  .back { display: block; text-align: center; margin-top: 18px; font-size: 14px; color: var(--brown); opacity: .7; text-decoration: none; }
</style>
</head>
<body>
  <main class="box">
    <img src="../88.png" alt="LOGO" onerror="this.style.display='none'">
    <h1>後台管理</h1>
    <p class="sub">請輸入管理員帳號密碼</p>

    <?php if ($error): ?><div class="msg err"><?= e($error) ?></div><?php endif; ?>
    <?php if ($notice && !$error): ?><div class="msg ok"><?= e($notice) ?></div><?php endif; ?>

    <form method="post" autocomplete="on">
      <?= csrf_field() ?>
      <label for="username">帳號</label>
      <input type="text" id="username" name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus autocomplete="username">

      <label for="password">密碼</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">

      <button type="submit">登入</button>
    </form>
    <a class="back" href="../index.php">← 回到前台</a>
  </main>
</body>
</html>
