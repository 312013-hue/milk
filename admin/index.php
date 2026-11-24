<?php
declare(strict_types=1);
require_once __DIR__ . '/_layout.php';

$pdo = db();

// 今日訂單與營收（已取消的不計入營收）
$today = $pdo->query("SELECT COUNT(*) c, COALESCE(SUM(total),0) s FROM orders
                      WHERE created_at >= CURDATE() AND status <> 'cancelled'")->fetch();
$month = $pdo->query("SELECT COUNT(*) c, COALESCE(SUM(total),0) s FROM orders
                      WHERE created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01') AND status <> 'cancelled'")->fetch();
$pending  = (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$products = $pdo->query("SELECT COUNT(*) total, COALESCE(SUM(is_active),0) active FROM products")->fetch();

// 近 7 日營收（沒有訂單的日子補 0）
$rows = $pdo->query("SELECT DATE(created_at) d, COUNT(*) c, SUM(total) s FROM orders
                     WHERE created_at >= CURDATE() - INTERVAL 6 DAY AND status <> 'cancelled'
                     GROUP BY DATE(created_at)")->fetchAll();
$byDay = [];
foreach ($rows as $r) {
    $byDay[$r['d']] = $r;
}
$days = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i day"));
    $days[] = ['label' => date('m/d', strtotime($d)), 'sum' => (int)($byDay[$d]['s'] ?? 0), 'today' => $i === 0];
}
$maxSum = max(1, ...array_column($days, 'sum'));

// 最新訂單、本月熱銷
$recent = $pdo->query("SELECT id, order_no, name, total, status, created_at FROM orders ORDER BY id DESC LIMIT 8")->fetchAll();
$top = $pdo->query("SELECT oi.name, SUM(oi.qty) q FROM order_items oi
                    JOIN orders o ON o.id = oi.order_id
                    WHERE o.created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01') AND o.status <> 'cancelled'
                    GROUP BY oi.name ORDER BY q DESC LIMIT 5")->fetchAll();

admin_header('儀表板', 'dashboard');
?>
<div class="cards">
  <div class="stat"><div class="label">今日訂單</div><div class="num"><?= (int)$today['c'] ?></div><div class="sub">不含已取消</div></div>
  <div class="stat"><div class="label">今日營收</div><div class="num"><?= money($today['s']) ?></div><div class="sub"><?= date('Y/m/d') ?></div></div>
  <div class="stat"><div class="label">本月營收</div><div class="num"><?= money($month['s']) ?></div><div class="sub">共 <?= (int)$month['c'] ?> 筆訂單</div></div>
  <div class="stat"><div class="label">待處理訂單</div><div class="num"><?= $pending ?></div><div class="sub"><a href="orders.php?status=pending">前往處理 ›</a></div></div>
  <div class="stat"><div class="label">上架商品</div><div class="num"><?= (int)$products['active'] ?></div><div class="sub">共 <?= (int)$products['total'] ?> 項商品</div></div>
</div>

<div class="two">
  <div class="panel">
    <h2>近 7 日營收</h2>
    <div class="bars">
      <?php foreach ($days as $d): ?>
        <div class="bar <?= $d['today'] ? 'today' : '' ?>">
          <b><?= $d['sum'] > 0 ? number_format($d['sum']) : '' ?></b>
          <i style="height: <?= round($d['sum'] / $maxSum * 100) ?>%"></i>
          <span><?= e($d['label']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="panel">
    <h2>本月熱銷商品</h2>
    <?php if (!$top): ?>
      <p class="empty">本月還沒有訂單</p>
    <?php else: ?>
      <table>
        <?php foreach ($top as $i => $t): ?>
          <tr><td><?= $i + 1 ?></td><td><?= e($t['name']) ?></td><td class="num"><?= (int)$t['q'] ?> 件</td></tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</div>

<div class="panel">
  <h2>最新訂單</h2>
  <?php if (!$recent): ?>
    <p class="empty">還沒有任何訂單</p>
  <?php else: ?>
    <div class="table-wrap" style="box-shadow:none">
      <table>
        <thead><tr><th>訂單編號</th><th>顧客</th><th class="num">金額</th><th>狀態</th><th>時間</th></tr></thead>
        <tbody>
        <?php foreach ($recent as $o): ?>
          <tr>
            <td><a href="orders.php?q=<?= urlencode($o['order_no']) ?>"><?= e($o['order_no']) ?></a></td>
            <td><?= e($o['name']) ?></td>
            <td class="num"><?= money($o['total']) ?></td>
            <td><span class="badge st-<?= e($o['status']) ?>"><?= e(ORDER_STATUS[$o['status']]) ?></span></td>
            <td><?= e(date('m/d H:i', strtotime($o['created_at']))) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?php admin_footer();
