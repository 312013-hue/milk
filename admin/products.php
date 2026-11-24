<?php
declare(strict_types=1);
require_once __DIR__ . '/_layout.php';

$pdo = db();
$cat = (int)($_GET['cat'] ?? $_POST['cat'] ?? 0);
$self = 'products.php' . ($cat > 0 ? '?cat=' . $cat : '');

// ===== 處理 POST：上下架、刪除 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        flash_set('err', '頁面已過期，請重試。');
    } else {
        $id = (int)($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($action === 'toggle') {
            $pdo->prepare('UPDATE products SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
            flash_set('ok', '已更新上架狀態。');
        } elseif ($action === 'delete') {
            $s = $pdo->prepare('SELECT image FROM products WHERE id = ?');
            $s->execute([$id]);
            $img = (string)$s->fetchColumn();
            $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
            // 只刪除後台上傳的圖片，不動網站原本的 4.jpg 等檔案
            if (str_starts_with($img, 'uploads/products/')) {
                @unlink(__DIR__ . '/../uploads/products/' . basename($img));
            }
            flash_set('ok', '商品已刪除。（歷史訂單的品名與價格仍會保留）');
        }
    }
    header('Location: ' . $self);
    exit;
}

// ===== 讀取 =====
$cats = $pdo->query('SELECT id, name FROM categories ORDER BY sort_order, id')->fetchAll();

$sql = 'SELECT p.*, c.name AS cat_name FROM products p JOIN categories c ON c.id = p.category_id';
$params = [];
if ($cat > 0) {
    $sql .= ' WHERE p.category_id = ?';
    $params[] = $cat;
}
$sql .= ' ORDER BY c.sort_order, c.id, p.sort_order, p.id';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$list = $stmt->fetchAll();

admin_header('商品管理', 'products');
?>
<div class="toolbar">
  <form method="get">
    <select name="cat" onchange="this.form.submit()">
      <option value="0">全部分類</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= $cat === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </form>
  <span class="grow"></span>
  <span>共 <?= count($list) ?> 項</span>
  <a class="btn" href="product_form.php">＋ 新增商品</a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr><th>圖片</th><th>名稱</th><th>分類</th><th class="num">價格</th><th>標籤</th><th>狀態</th><th>操作</th></tr>
    </thead>
    <tbody>
    <?php if (!$list): ?>
      <tr><td colspan="7" class="empty">這個分類還沒有商品</td></tr>
    <?php endif; ?>
    <?php foreach ($list as $p): ?>
      <?php $opts = $p['options'] ? json_decode($p['options'], true) : null; ?>
      <tr class="<?= $p['is_active'] ? '' : 'row-off' ?>">
        <td><img class="thumb" src="<?= e(img_url($p['image'])) ?>" alt="" onerror="this.style.visibility='hidden'"></td>
        <td>
          <strong><?= e($p['name']) ?></strong>
          <div style="font-size:13px;opacity:.65"><?= e($p['description']) ?></div>
          <?php if ($opts): ?>
            <div style="font-size:13px;color:var(--brown)">規格：<?= e(implode('、', array_column($opts, 'label'))) ?></div>
          <?php endif; ?>
        </td>
        <td><?= e($p['cat_name']) ?></td>
        <td class="num"><?= money($p['price']) ?><?= $opts ? ' 起' : '' ?></td>
        <td><?= $p['tag'] !== '' ? '<span class="badge">' . e($p['tag']) . '</span>' : '' ?></td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="toggle">
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <input type="hidden" name="cat" value="<?= $cat ?>">
            <button type="submit" class="badge <?= $p['is_active'] ? 'on-badge' : 'off-badge' ?>" style="border:0;cursor:pointer" title="點擊切換">
              <?= $p['is_active'] ? '上架中' : '已下架' ?>
            </button>
          </form>
        </td>
        <td>
          <div class="actions">
            <a class="btn ghost sm" href="product_form.php?id=<?= (int)$p['id'] ?>">編輯</a>
            <form method="post" onsubmit="return confirm('確定刪除「<?= e(addslashes($p['name'])) ?>」？此動作無法復原。')">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
              <input type="hidden" name="cat" value="<?= $cat ?>">
              <button type="submit" class="btn danger sm">刪除</button>
            </form>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php admin_footer();
