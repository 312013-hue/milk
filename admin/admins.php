<?php
declare(strict_types=1);
require_once __DIR__ . '/_layout.php';

$pdo = db();
$meId = (int)$_SESSION['admin_id'];

function valid_username(string $u): bool
{
    return (bool)preg_match('/^[A-Za-z0-9_]{3,50}$/', $u);
}

// ===== 處理 POST =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);

    if (!csrf_verify()) {
        flash_set('err', '頁面已過期，請重試。');
    } elseif ($action === 'add') {
        $u  = trim((string)($_POST['username'] ?? ''));
        $p1 = (string)($_POST['password'] ?? '');
        $p2 = (string)($_POST['password2'] ?? '');
        if (!valid_username($u))        flash_set('err', '帳號需為 3–50 字的英數字或底線。');
        elseif (strlen($p1) < 8)        flash_set('err', '密碼至少 8 個字元。');
        elseif ($p1 !== $p2)            flash_set('err', '兩次密碼不一致。');
        else {
            try {
                $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)')
                    ->execute([$u, password_hash($p1, PASSWORD_DEFAULT)]);
                flash_set('ok', "已新增管理員「{$u}」。");
            } catch (PDOException $ex) {
                flash_set('err', $ex->getCode() === '23000' ? '這個帳號已經存在。' : '新增失敗，請稍後再試。');
            }
        }
    } elseif ($action === 'password') {
        $p1 = (string)($_POST['password'] ?? '');
        if (strlen($p1) < 8) {
            flash_set('err', '新密碼至少 8 個字元。');
        } else {
            $pdo->prepare('UPDATE admins SET password_hash = ? WHERE id = ?')
                ->execute([password_hash($p1, PASSWORD_DEFAULT), $id]);
            if ($id === $meId) session_regenerate_id(true);
            flash_set('ok', '密碼已更新。');
        }
    } elseif ($action === 'delete') {
        $count = (int)$pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        if ($id === $meId)      flash_set('err', '不能刪除目前登入中的自己。');
        elseif ($count <= 1)    flash_set('err', '至少要保留一個管理員。');
        else {
            $pdo->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
            flash_set('ok', '管理員已刪除。');
        }
    }
    header('Location: admins.php');
    exit;
}

$admins = $pdo->query('SELECT id, username, created_at, last_login FROM admins ORDER BY id')->fetchAll();

admin_header('管理員管理', 'admins');
?>
<div class="table-wrap" style="margin-bottom:24px">
  <table>
    <thead><tr><th>帳號</th><th>建立時間</th><th>上次登入</th><th>操作</th></tr></thead>
    <tbody>
    <?php foreach ($admins as $a): $isMe = (int)$a['id'] === $meId; ?>
      <tr>
        <td><strong><?= e($a['username']) ?></strong><?= $isMe ? ' <span class="badge on-badge">目前登入</span>' : '' ?></td>
        <td><?= e(date('Y/m/d H:i', strtotime($a['created_at']))) ?></td>
        <td><?= $a['last_login'] ? e(date('Y/m/d H:i', strtotime($a['last_login']))) : '—' ?></td>
        <td>
          <div class="actions">
            <form method="post" style="display:flex;gap:6px" onsubmit="return confirm('確定修改「<?= e(addslashes($a['username'])) ?>」的密碼？')">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="password">
              <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
              <input type="password" name="password" placeholder="新密碼（8 字元以上）" minlength="8" required autocomplete="new-password" style="width:200px;padding:4px 10px">
              <button class="btn ghost sm" type="submit">改密碼</button>
            </form>
            <?php if (!$isMe): ?>
              <form method="post" onsubmit="return confirm('確定刪除管理員「<?= e(addslashes($a['username'])) ?>」？')">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                <button class="btn danger sm" type="submit">刪除</button>
              </form>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<form class="panel form" method="post">
  <h2>新增管理員</h2>
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="add">
  <div class="field">
    <label for="username">帳號</label>
    <input type="text" id="username" name="username" maxlength="50" required autocomplete="off">
    <div class="hint">3–50 字，只能用英文、數字、底線。</div>
  </div>
  <div class="grid2">
    <div class="field">
      <label for="password">密碼</label>
      <input type="password" id="password" name="password" minlength="8" required autocomplete="new-password">
    </div>
    <div class="field">
      <label for="password2">再輸入一次</label>
      <input type="password" id="password2" name="password2" minlength="8" required autocomplete="new-password">
    </div>
  </div>
  <button class="btn" type="submit">新增</button>
</form>
<?php admin_footer();
