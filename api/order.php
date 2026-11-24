<?php
declare(strict_types=1);
// 接收前台訂單。價格一律由伺服器依資料庫重新計算，不信任前端傳來的價格。
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function out(array $d, int $code = 200): never
{
    http_response_code($code);
    echo json_encode($d, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    out(['ok' => false, 'error' => 'Method not allowed'], 405);
}

$in = json_decode((string)file_get_contents('php://input'), true);
if (!is_array($in)) {
    out(['ok' => false, 'error' => '資料格式錯誤。'], 400);
}

// 蜜罐欄位：真人看不到也不會填，機器人填了就假裝成功
if (($in['website'] ?? '') !== '') {
    out(['ok' => true, 'order_no' => 'OK', 'total' => 0]);
}

$name  = trim((string)($in['name'] ?? ''));
$phone = trim((string)($in['phone'] ?? ''));
$note  = trim((string)($in['note'] ?? ''));

if ($name === '' || mb_strlen($name) > 50)   out(['ok' => false, 'error' => '請填寫姓名（50 字內）。'], 422);
if (!preg_match('/^09\d{8}$/', $phone))      out(['ok' => false, 'error' => '請輸入正確手機號碼（09 開頭 10 位數）。'], 422);
if (mb_strlen($note) > 255)                  out(['ok' => false, 'error' => '備註最多 255 字。'], 422);

$rawItems = $in['items'] ?? null;
if (!is_array($rawItems) || !$rawItems || count($rawItems) > 50) {
    out(['ok' => false, 'error' => '購物車是空的。'], 422);
}

$pdo = db();

// 建立「前台購物車品名 → 資料庫商品」對照表（只含上架中的商品）
$map = [];
foreach ($pdo->query('SELECT id, name, price, options FROM products WHERE is_active = 1') as $p) {
    $opts = $p['options'] ? json_decode($p['options'], true) : null;
    if ($opts) {
        // 有規格的商品必須選規格；前台購物車的命名是「商品簡稱（規格）」
        $base = preg_replace('/（[^）]*）\s*$/u', '', $p['name']);
        foreach ($opts as $o) {
            $entry = ['id' => (int)$p['id'], 'name' => $p['name'] . '（' . $o['label'] . '）', 'price' => (int)$o['price']];
            $map[$p['name'] . '（' . $o['label'] . '）'] = $entry;
            $map[$base . '（' . $o['label'] . '）']      = $entry;
        }
    } else {
        $map[$p['name']] = ['id' => (int)$p['id'], 'name' => $p['name'], 'price' => (int)$p['price']];
    }
}

// 逐項驗證、合併重複品項、計算總額
$lines = [];
$total = 0;
foreach ($rawItems as $it) {
    $iname = trim((string)($it['name'] ?? ''));
    $qty   = $it['qty'] ?? 0;
    if (!is_int($qty) || $qty < 1 || $qty > 99) {
        out(['ok' => false, 'error' => '商品數量不正確。'], 422);
    }
    if (!isset($map[$iname])) {
        out(['ok' => false, 'error' => "「{$iname}」目前無法訂購（可能已下架或名稱已更新），請從購物車移除後再試。"], 422);
    }
    $m = $map[$iname];
    $lines[$m['name']] ??= $m + ['qty' => 0];
    $lines[$m['name']]['qty'] += $qty;
}
foreach ($lines as $l) {
    $total += $l['price'] * $l['qty'];
}

// 簡單防洗單：同一支手機 10 分鐘內最多 3 筆
$s = $pdo->prepare('SELECT COUNT(*) FROM orders WHERE phone = ? AND created_at >= NOW() - INTERVAL 10 MINUTE');
$s->execute([$phone]);
if ((int)$s->fetchColumn() >= 3) {
    out(['ok' => false, 'error' => '送出太頻繁，請稍後再試，或直接來電聯絡我們。'], 429);
}

// 寫入訂單與明細（交易：要嘛全成功，要嘛全不寫）
try {
    $pdo->beginTransaction();

    $orderNo = '';
    for ($try = 0; $try < 5; $try++) {
        $orderNo = 'YH' . date('ymdHis') . random_int(100, 999);
        try {
            $pdo->prepare('INSERT INTO orders (order_no, name, phone, note, total) VALUES (?,?,?,?,?)')
                ->execute([$orderNo, $name, $phone, $note, $total]);
            break;
        } catch (PDOException $ex) {
            if ($ex->getCode() !== '23000' || $try === 4) throw $ex;   // 編號重複才重試
        }
    }
    $orderId = (int)$pdo->lastInsertId();

    $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, name, price, qty) VALUES (?,?,?,?,?)');
    foreach ($lines as $l) {
        $ins->execute([$orderId, $l['id'], $l['name'], $l['price'], $l['qty']]);
    }
    $pdo->commit();
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log('order failed: ' . $ex->getMessage());
    out(['ok' => false, 'error' => '訂單送出失敗，請稍後再試。'], 500);
}

out(['ok' => true, 'order_no' => $orderNo, 'total' => $total]);