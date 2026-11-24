<?php
declare(strict_types=1);
require_once __DIR__ . '/_layout.php';

$pdo = db();

// ===== 處理 POST：改狀態、刪除 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $back = 'orders.php' . (($_POST['back'] ?? '') !== '' ? '?' . ltrim((string)$_POST['back'], '?') : '');
    if (!csrf_verify()) {
        flash_set('err', '頁面已過期，請重試。');
    } else {
        $id = (int)($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';
        if ($action === 'status' && isset(ORDER_STATUS[$_POST['status'] ?? ''])) {
            $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$_POST['status'], $id]);
            flash_set('ok', '訂單狀態已更新為「' . ORDER_STATUS[$_POST['status']] . '」。');
        } elseif ($action === 'delete') {
            $pdo->prepare('DELETE FROM orders WHERE id = ?')->execute([$id]);   // order_items 會連動刪除
            flash_set('ok', '訂單已刪除。');
        }
    }
    header('Location: ' . $back);
    exit;
}

// ===== 篩選、搜尋、分頁 =====
$status = (string)($_GET['status'] ?? '');
$q      = trim((string)($_GET['q'] ?? ''));
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = 20;

$where = [];
$params = [];
if (isset(ORDER_STATUS[$status])) {
    $where[] = 'status = ?';
    $params[] = $status;
} else {
    $status = '';
}
if ($q !== '') {
    $where[] = '(order_no LIKE ? OR name LIKE ? OR phone LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
$sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders $sqlWhere");
$stmt->execute($params);
$total = (int)$stmt->fetchColumn();
$pages = max(1, (int)ceil($total / $per));
$page  = min($page, $pages);
$offset = ($page - 1) * $per;

$stmt = $pdo->prepare("SELECT * FROM orders $sqlWhere ORDER BY id DESC LIMIT $per OFFSET $offset");
$stmt->execute($params);
$orders = $stmt->fetchAll();

// 一次撈出這一頁所有訂單的品項
$items = [];
if ($orders) {
    $ids = array_column($orders, 'id');
    $in = implode(',', array_fill(0, count($ids), '?'));
    $s = $pdo->prepare("SELECT order_id, name, price, qty FROM order_items WHERE order_id IN ($in) ORDER BY id");
    $s->execute($ids);
    foreach ($s->fetchAll() as $it) {
        $items[$it['order_id']][] = $it;
    }
}

// 各狀態數量（分頁籤用）
$counts = $pdo->query('SELECT status, COUNT(*) c FROM orders GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$allCount = array_sum($counts);

$qs = fn(array $extra = []) => http_build_query(array_filter(
    array_merge(['status' => $status, 'q' => $q, 'page' => $page > 1 ? $page : null], $extra),
    fn($v) => $v !== '' && $v !== null
));

admin_header('訂單管理', 'orders');
?>
<div class="tabs">
  <a href="orders.php" class="<?= $status === '' ? 'on' : '' ?>">全部 (<?= $allCount ?>)</a>
  <?php foreach (ORDER_STATUS as $k => $label): ?>
    <a href="orders.php?status=<?= $k ?>" class="<?= $status === $k ? 'on' : '' ?>"><?= $label ?> (<?= (int)($counts[$k] ?? 0) ?>)</a>
  <?php endforeach; ?>
</div>

<form class="toolbar" method="get">
  <?php if ($status !== ''): ?><input type="hidden" name="status" value="<?= e($status) ?>"><?php endif; ?>
  <input type="search" name="q" value="<?= e($q) ?>" placeholder="搜尋訂單編號、姓名、電話">
  <button class="btn" type="submit">搜尋</button>
  <?php if ($q !== ''): ?><a class="btn ghost" href="orders.php<?= $status ? '?status=' . e($status) : '' ?>">清除</a><?php endif; ?>
  <span class="grow"></span>
  <span>共 <?= $total ?> 筆</span>
</form>

<div class="table-wrap">
  <table>
    <thead>
      <tr><th>訂單編號</th><th>顧客</th><th>電話</th><th class="num">金額</th><th>狀態</th><th>下單時間</th><th>操作</th></tr>
    </thead>
    <tbody>
    <?php if (!$orders): ?>
      <tr><td colspan="7" class="empty">沒有符合的訂單</td></tr>
    <?php endif; ?>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><?= e($o['order_no']) ?></td>
        <td><?= e($o['name']) ?></td>
        <td><a href="tel:<?= e($o['phone']) ?>"><?= e($o['phone']) ?></a></td>
        <td class="num"><?= money($o['total']) ?></td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="status">
            <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
            <input type="hidden" name="back" value="<?= e($qs()) ?>">
            <select name="status" class="st-<?= e($o['status']) ?>" style="width:auto;padding:4px 8px" onchange="this.form.submit()">
              <?php foreach (ORDER_STATUS as $k => $label): ?>
                <option value="<?= $k ?>" <?= $o['status'] === $k ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </td>
        <td><?= e(date('Y/m/d H:i', strtotime($o['created_at']))) ?></td>
        <td>
          <div class="actions">
            <button type="button" class="btn ghost sm" onclick="toggleDetail(<?= (int)$o['id'] ?>)">明細</button>
            <form method="post" onsubmit="return confirm('確定刪除訂單 <?= e($o['order_no']) ?>？此動作無法復原。')">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$o['id'] ?>">
              <input type="hidden" name="back" value="<?= e($qs()) ?>">
              <button type="submit" class="btn danger sm">刪除</button>
            </form>
          </div>
        </td>
      </tr>
      <tr class="detail-row" id="d<?= (int)$o['id'] ?>" hidden>
        <td colspan="7">
          <ul>
            <?php foreach ($items[$o['id']] ?? [] as $it): ?>
              <li><span><?= e($it['name']) ?> × <?= (int)$it['qty'] ?></span><span><?= money($it['price'] * $it['qty']) ?></span></li>
            <?php endforeach; ?>
          </ul>
          <?php if ($o['note'] !== ''): ?><p style="margin-top:6px">備註：<?= e($o['note']) ?></p><?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php if ($pages > 1): ?>
  <div class="pager">
    <?php for ($p = 1; $p <= $pages; $p++): ?>
      <?php if ($p === $page): ?><span><?= $p ?></span>
      <?php else: ?><a href="orders.php?<?= $qs(['page' => $p > 1 ? $p : null]) ?>"><?= $p ?></a><?php endif; ?>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<script>
function toggleDetail(id) {
  var r = document.getElementById('d' + id);
  r.hidden = !r.hidden;
}
</script>
<?php admin_footer();
