<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>永和豆漿｜結帳</title>
<link rel="stylesheet" href="style.css">
<style>
  .co { max-width: 900px; margin: 0 auto; padding: 40px 20px 100px; }
  .co h1 { font-size: 40px; font-weight: normal; color: var(--brown, #7a5c47); text-align: center; margin-bottom: 28px; }
  .co-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 28px; align-items: start; }
  .co-box { background: #fff; border-radius: 16px; padding: 26px; box-shadow: 0 4px 16px rgba(0,0,0,.06); }
  .co-box h2 { font-size: 22px; font-weight: normal; color: var(--brown, #7a5c47); margin-bottom: 14px; }
  .co-item { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #eee3d6; }
  .co-item .nm { flex: 1; }
  .co-item .pr { font-size: 14px; opacity: .65; }
  .co-qty { display: flex; align-items: center; gap: 8px; }
  .co-qty button { width: 28px; height: 28px; border: 1px solid #c9ad92; background: #fff; border-radius: 50%; cursor: pointer; font-size: 16px; line-height: 1; }
  .co-total { display: flex; justify-content: space-between; margin-top: 16px; font-size: 20px; color: var(--brown, #7a5c47); }
  .co-hint { font-size: 13px; opacity: .6; margin-top: 8px; }
  .co label { display: block; margin: 14px 0 6px; font-size: 15px; color: var(--brown, #7a5c47); }
  .co input[type=text], .co input[type=tel], .co textarea { width: 100%; padding: 11px 13px; font: inherit; font-size: 17px; border: 1px solid #c9ad92; border-radius: 10px; outline: none; }
  .co input:focus, .co textarea:focus { border-color: var(--brown, #7a5c47); box-shadow: 0 0 0 3px rgba(122,92,71,.15); }
  .co-submit { width: 100%; margin-top: 22px; padding: 13px; font: inherit; font-size: 19px; color: #fff; background: var(--brown, #7a5c47); border: 0; border-radius: 999px; cursor: pointer; }
  .co-submit:hover { background: #5f4837; }
  .co-submit:disabled { opacity: .5; cursor: not-allowed; }
  .co-err { display: none; margin-top: 14px; padding: 10px 14px; background: #fdecea; color: #a8321f; border-radius: 10px; font-size: 15px; }
  .co-empty, .co-done { text-align: center; padding: 30px 10px; }
  .co-done .no { font-size: 24px; color: var(--brown, #7a5c47); margin: 10px 0; letter-spacing: 1px; }
  .co-link { display: inline-block; margin-top: 18px; padding: 10px 30px; color: #fff; background: var(--brown, #7a5c47); border-radius: 999px; }
  [hidden] { display: none !important; }
  .done-modal { position: fixed; inset: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.45); animation: fadeIn .25s ease; }
  .done-box { width: 100%; max-width: 420px; padding: 34px 28px 30px; text-align: center; background: #fff; border-radius: 20px; box-shadow: 0 16px 48px rgba(0,0,0,.25); animation: popIn .3s cubic-bezier(.2,1.2,.4,1); }
  .done-emoji { font-size: 52px; }
  .done-box h2 { margin: 6px 0 4px; font-size: 24px; font-weight: normal; color: var(--brown, #7a5c47); }
  .done-box .no { margin: 12px 0 8px; font-size: 22px; letter-spacing: 1px; color: var(--brown, #7a5c47); word-break: break-all; }
  .done-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 8px; }
  .co-link.ghost { color: var(--brown, #7a5c47); background: #fff; border: 1px solid var(--brown, #7a5c47); }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @keyframes popIn { from { opacity: 0; transform: scale(.85) translateY(10px); } to { opacity: 1; transform: none; } }
  .hp { position: absolute; left: -9999px; }
  @media (max-width: 768px) { .co-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<nav>
  <div class="nav-links">
    <div class="nav-item">
      <button type="button" class="nav-label" aria-haspopup="true" aria-expanded="false">關於我們<span class="arrow-down"></span></button>
      <div class="dropdown">
        <a href="about.php#story">品牌故事</a>
        <a href="about.php#philosophy">品牌理念</a>
        <a href="about.php#features">品牌特色</a>
      </div>
    </div>
    <a href="https://www.instagram.com/jd.122630/">最新活動</a>
    <div class="nav-item">
      <button type="button" class="nav-label" aria-haspopup="true" aria-expanded="false">品項<span class="arrow-down"></span></button>
      <div class="dropdown">
        <a href="products.php#gift">禮盒</a>
        <a href="products.php#fruit">果乾</a>
        <a href="products.php#nougat">牛軋糖</a>
        <a href="products.php#jerky">肉乾</a>
        <a href="products.php#other">其他</a>
      </div>
    </div>
    <a href="#" id="cart-link">購物車 (<span id="cart-count">0</span>)</a>
  </div>
</nav>

<main class="co">
  <h1>結帳</h1>

  <div id="view-form" class="co-grid">
    <section class="co-box">
      <h2>訂購內容</h2>
      <div id="items"></div>
      <div class="co-total"><span>總金額</span><strong id="total">NT$ 0</strong></div>
      <p class="co-hint">實際金額以送出訂單時的商品定價為準。</p>
    </section>

    <section class="co-box">
      <h2>訂購人資料</h2>
      <form id="order-form" novalidate>
        <label for="buyer-name">姓名 *</label>
        <input type="text" id="buyer-name" maxlength="50" autocomplete="name">

        <label for="buyer-phone">手機號碼 *</label>
        <input type="tel" id="buyer-phone" maxlength="10" inputmode="numeric" placeholder="0912345678" autocomplete="tel">

        <label for="buyer-note">備註（選填）</label>
        <textarea id="buyer-note" rows="3" maxlength="255" placeholder="例如：希望取貨時間"></textarea>

        <input type="text" id="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">

        <div class="co-err" id="err"></div>
        <button type="submit" class="co-submit" id="submit-btn">送出訂單</button>
      </form>
    </section>
  </div>

</main>

<div class="done-modal" id="done-modal" role="dialog" aria-modal="true" aria-labelledby="done-title" hidden>
  <div class="done-box">
    <div class="done-emoji">🎉</div>
    <h2 id="done-title">訂單已送出，謝謝您！</h2>
    <div class="no" id="done-no"></div>
    <p>訂單金額：<strong id="done-total"></strong></p>
    <p class="co-hint" style="font-size:15px;margin-bottom:16px">請記下訂單編號，店家會以電話與您聯絡確認後續事宜。</p>
    <div class="done-actions">
      <a class="co-link" id="done-first" href="products.php">繼續逛逛</a>
      <a class="co-link ghost" href="index.php">回首頁</a>
    </div>
  </div>
</div>

<div class="cart-modal"><div class="cart-box"></div></div>

<footer class="yonghe-footer">
  <div class="copyright">
    <p>© 2026 永和豆漿 版權所有 ・ 用心製作・只為給你最好的早餐</p>
  </div>
</footer>

<script src="script.js"></script>
<script>
(function () {
  var itemsEl = document.getElementById('items');
  var totalEl = document.getElementById('total');
  var errEl   = document.getElementById('err');
  var btn     = document.getElementById('submit-btn');

  function render() {
    if (cart.length === 0) {
      itemsEl.innerHTML = '<div class="co-empty">購物車是空的<br><a class="co-link" href="products.php">去逛逛商品</a></div>';
      totalEl.textContent = 'NT$ 0';
      btn.disabled = true;
      return;
    }
    btn.disabled = false;
    var html = '', sum = 0;
    cart.forEach(function (it, idx) {
      sum += it.price * it.qty;
      html += '<div class="co-item"><div class="nm">' + escapeHTML(it.name) +
        '<div class="pr">NT$ ' + it.price + '</div></div>' +
        '<div class="co-qty"><button type="button" data-i="' + idx + '" data-d="-1" aria-label="減少">−</button>' +
        '<span>' + it.qty + '</span>' +
        '<button type="button" data-i="' + idx + '" data-d="1" aria-label="增加">+</button></div></div>';
    });
    itemsEl.innerHTML = html;
    totalEl.textContent = 'NT$ ' + sum;
  }

  itemsEl.addEventListener('click', function (e) {
    var b = e.target.closest('button[data-i]');
    if (!b) return;
    changeQty(Number(b.dataset.i), Number(b.dataset.d));   // script.js 內的函式，會一併存檔
    render();
  });

  function showError(msg) { errEl.textContent = msg; errEl.style.display = 'block'; }

  document.getElementById('order-form').addEventListener('submit', function (e) {
    e.preventDefault();
    errEl.style.display = 'none';

    var name  = document.getElementById('buyer-name').value.trim();
    var phone = document.getElementById('buyer-phone').value.trim();
    var note  = document.getElementById('buyer-note').value.trim();

    if (!name) return showError('請填寫姓名。');
    if (!/^09[0-9]{8}$/.test(phone)) return showError('請輸入正確手機號碼（09 開頭 10 位數）。');
    if (cart.length === 0) return showError('購物車是空的。');

    btn.disabled = true;
    btn.textContent = '送出中…';

    fetch('api/order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name, phone: phone, note: note,
        website: document.getElementById('website').value,
        items: cart.map(function (i) { return { name: i.name, qty: i.qty }; })   // 不傳價格，由伺服器計算
      })
    })
    .then(function (r) { return r.json().catch(function () { return { ok: false, error: '伺服器回應異常，請稍後再試。' }; }); })
    .then(function (res) {
      if (!res.ok) throw new Error(res.error || '送出失敗，請稍後再試。');
      cart = [];
      saveCart();
      document.getElementById('done-no').textContent = res.order_no;
      document.getElementById('done-total').textContent = 'NT$ ' + res.total;
      document.getElementById('view-form').hidden = true;
      document.getElementById('done-modal').hidden = false;
      document.body.style.overflow = 'hidden';
      document.getElementById('done-first').focus();
    })
    .catch(function (err) {
      showError(err.message);
      btn.disabled = false;
      btn.textContent = '送出訂單';
    });
  });

  render();
})();
</script>
</body>
</html>