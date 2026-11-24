<?php
declare(strict_types=1);
require_once __DIR__ . '/_layout.php';

$pdo = db();
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$cats = $pdo->query('SELECT id, name FROM categories ORDER BY sort_order, id')->fetchAll();
$catIds = array_map('intval', array_column($cats, 'id'));

const MAX_IMG_BYTES = 3 * 1024 * 1024;   // 圖片上限 3MB

/** 規格文字（每行「名稱=價格」）轉成陣列 */
function parse_options(string $raw, string &$err): ?array
{
    $out = [];
    foreach (preg_split('/\R/u', trim($raw)) as $line) {
        $line = trim($line);
        if ($line === '') continue;
        if (!preg_match('/^(.+?)\s*[=＝]\s*(\d+)$/u', $line, $m)) {
            $err = '規格格式錯誤：「' . $line . '」，請用「名稱=價格」，例如 三入組=175';
            return null;
        }
        $out[] = ['label' => trim($m[1]), 'price' => (int)$m[2]];
    }
    return $out ?: null;
}

/** 處理圖片上傳，成功回傳相對路徑；沒選檔案回傳 null；失敗則設定 $err */
function handle_upload(array $f, string &$err): ?string
{
    if ($f['error'] === UPLOAD_ERR_NO_FILE) return null;
    if ($f['error'] !== UPLOAD_ERR_OK) {
        $err = $f['error'] === UPLOAD_ERR_INI_SIZE || $f['error'] === UPLOAD_ERR_FORM_SIZE
            ? '圖片太大（上限 3MB）。' : '圖片上傳失敗（錯誤碼 ' . $f['error'] . '）。';
        return null;
    }
    if ($f['size'] > MAX_IMG_BYTES) { $err = '圖片太大（上限 3MB）。'; return null; }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if ($ext === null || @getimagesize($f['tmp_name']) === false) {
        $err = '只接受 JPG、PNG、WebP 圖片。';
        return null;
    }

    $dir = __DIR__ . '/../uploads/products';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) { $err = '無法建立上傳資料夾。'; return null; }

    $name = bin2hex(random_bytes(8)) . '.' . $ext;   // 隨機檔名，不使用使用者提供的檔名
    if (!move_uploaded_file($f['tmp_name'], $dir . '/' . $name)) { $err = '儲存圖片失敗。'; return null; }
    return 'uploads/products/' . $name;
}

// ===== 載入既有資料（編輯）或預設值（新增）=====
$data = ['name' => '', 'category_id' => $catIds[0] ?? 0, 'description' => '', 'price' => '', 'options' => '',
         'tag' => '', 'sort_order' => 0, 'is_active' => 1, 'image' => ''];

if ($id > 0) {
    $s = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $s->execute([$id]);
    $row = $s->fetch();
    if (!$row) {
        flash_set('err', '找不到這個商品。');
        header('Location: products.php');
        exit;
    }
    $data = $row;
    $opts = $row['options'] ? json_decode($row['options'], true) : [];
    $data['options'] = implode("\n", array_map(fn($o) => $o['label'] . '=' . $o['price'], $opts ?: []));
}

$err = '';

// ===== 儲存 =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in = [
        'name'        => trim((string)($_POST['name'] ?? '')),
        'category_id' => (int)($_POST['category_id'] ?? 0),
        'description' => trim((string)($_POST['description'] ?? '')),
        'price'       => trim((string)($_POST['price'] ?? '')),
        'options'     => (string)($_POST['options'] ?? ''),
        'tag'         => trim((string)($_POST['tag'] ?? '')),
        'sort_order'  => (int)($_POST['sort_order'] ?? 0),
        'is_active'   => isset($_POST['is_active']) ? 1 : 0,
    ];
    $data = array_merge($data, $in);

    $opts = parse_options($in['options'], $err);

    if ($err === '') {
        if (!csrf_verify())                                   $err = '頁面已過期，請重新整理後再試。';
        elseif ($in['name'] === '' || mb_strlen($in['name']) > 100) $err = '請填寫商品名稱（100 字內）。';
        elseif (!in_array($in['category_id'], $catIds, true)) $err = '請選擇分類。';
        elseif (mb_strlen($in['description']) > 255)          $err = '簡介最多 255 字。';
        elseif (mb_strlen($in['tag']) > 20)                   $err = '標籤最多 20 字。';
        elseif ($opts === null && !preg_match('/^\d{1,7}$/', $in['price'])) $err = '請填寫正確的價格（整數）。';
    }

    $newImage = null;
    if ($err === '') {
        $newImage = handle_upload($_FILES['image'] ?? ['error' => UPLOAD_ERR_NO_FILE], $err);
    }

    if ($err === '') {
        $price = $opts ? min(array_column($opts, 'price')) : (int)$in['price'];
        $optJson = $opts ? json_encode($opts, JSON_UNESCAPED_UNICODE) : null;
        $oldImage = (string)$data['image'];
        $image = $newImage ?? $oldImage;

        if ($id > 0) {
            $pdo->prepare('UPDATE products SET category_id=?, name=?, description=?, price=?, options=?, tag=?, image=?, is_active=?, sort_order=? WHERE id=?')
                ->execute([$in['category_id'], $in['name'], $in['description'], $price, $optJson, $in['tag'], $image, $in['is_active'], $in['sort_order'], $id]);
            // 換了新圖，才刪掉舊的上傳圖片
            if ($newImage !== null && str_starts_with($oldImage, 'uploads/products/')) {
                @unlink(__DIR__ . '/../uploads/products/' . basename($oldImage));
            }
            flash_set('ok', '商品已更新。');
        } else {
            $pdo->prepare('INSERT INTO products (category_id, name, description, price, options, tag, image, is_active, sort_order) VALUES (?,?,?,?,?,?,?,?,?)')
                ->execute([$in['category_id'], $in['name'], $in['description'], $price, $optJson, $in['tag'], $image, $in['is_active'], $in['sort_order']]);
            flash_set('ok', '商品已新增。');
        }
        header('Location: products.php?cat=' . $in['category_id']);
        exit;
    }
}

admin_header($id > 0 ? '編輯商品' : '新增商品', 'products');
?>
<?php if ($err): ?><div class="flash err"><?= e($err) ?></div><?php endif; ?>

<form class="panel form" method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= $id ?>">

  <div class="field">
    <label for="name">商品名稱 *</label>
    <input type="text" id="name" name="name" maxlength="100" value="<?= e($data['name']) ?>" required>
  </div>

  <div class="grid2">
    <div class="field">
      <label for="category_id">分類 *</label>
      <select id="category_id" name="category_id">
        <?php foreach ($cats as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= (int)$data['category_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label for="tag">標籤（選填）</label>
      <input type="text" id="tag" name="tag" maxlength="20" value="<?= e($data['tag']) ?>" placeholder="熱銷、新品、伴手禮…">
    </div>
  </div>

  <div class="field">
    <label for="description">簡介</label>
    <input type="text" id="description" name="description" maxlength="255" value="<?= e($data['description']) ?>">
  </div>

  <div class="grid2">
    <div class="field">
      <label for="price">價格（NT$）</label>
      <input type="number" id="price" name="price" min="0" step="1" value="<?= e($data['price']) ?>">
      <div class="hint">若下方有填規格，價格會自動取規格中最低價。</div>
    </div>
    <div class="field">
      <label for="sort_order">排序</label>
      <input type="number" id="sort_order" name="sort_order" step="1" value="<?= e($data['sort_order']) ?>">
      <div class="hint">數字越小越前面。</div>
    </div>
  </div>

  <div class="field">
    <label for="options">規格（選填）</label>
    <textarea id="options" name="options" rows="3" placeholder="三入組=175&#10;六入組=350"><?= e($data['options']) ?></textarea>
    <div class="hint">每行一個規格，格式「名稱=價格」。沒有多種規格就留空。</div>
  </div>

  <div class="field">
    <label for="image">商品圖片</label>
    <?php if ($data['image'] !== ''): ?>
      <img class="preview" src="<?= e(img_url($data['image'])) ?>" alt="目前圖片" onerror="this.style.display='none'">
    <?php endif; ?>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <div class="hint">JPG / PNG / WebP，3MB 以內。<?= $id > 0 ? '不選檔案則保留目前圖片。' : '' ?></div>
  </div>

  <div class="field check">
    <input type="checkbox" id="is_active" name="is_active" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
    <label for="is_active" style="margin:0">上架（前台可見）</label>
  </div>

  <div class="actions">
    <button class="btn" type="submit">儲存</button>
    <a class="btn ghost" href="products.php">取消</a>
  </div>
</form>
<?php admin_footer();
