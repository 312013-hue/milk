<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>關於我們｜永和豆漿</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="about.css">
</head>
<body>

<!-- ===== 導覽列（和首頁相同）===== -->
<nav>
  <div class="nav-links">

    <div class="nav-item">
      <button type="button" class="nav-label active" aria-haspopup="true" aria-expanded="false">關於我們<span class="arrow-down"></span></button>
      <div class="dropdown">
        <a href="#story">品牌故事</a>
        <a href="#philosophy">品牌理念</a>
        <a href="#features">品牌特色</a>
      </div>
    </div>

    <a href="https://www.instagram.com/jd.122630/" target="_blank">最新活動</a>

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

<!-- ===== 頁首 ===== -->
<header class="about-hero">
  <div class="about-logo-wrap">
    <span class="deco d1">🌾</span>
    <span class="deco d2">🥛</span>
    <span class="deco d3">🥟</span>
    <span class="deco d4">☕</span>

      <img class="about-logo" src="88.png" alt="LOGO">
  </div>

  <h1>關於我們</h1>
  <p>從傳統早餐出發，重新發現日常裡的美好</p>

  <div class="hero-tags">
    <span>🕕 清晨六點開賣</span>
    <span>👨‍👩‍👧 傳承三代</span>
    <span>📍 台大公館商圈</span>
  </div>
</header>

<!-- ===== 品牌故事 ===== -->
<section id="story" class="about-section">
  <div class="section-title">
    <h2>品牌故事</h2>
    <small>Our Story</small>
  </div>

  <div class="story-row">
    <div class="story-img">
      <!-- 換成你的圖片，例如店面照片 -->
      <img src="11.jpg" alt="永和豆漿公館店">
    </div>
    <div class="story-text">
      <h3>好味道的背後，是每一份認真。</h3>
      <p>
        永和豆漿公館店位在台大商圈，每天清晨六點開始，用心熬煮每一碗豆漿，
        陪伴附近的學生、上班族與街坊鄰居展開新的一天。
      </p>
      <p>
        一份簡單的早餐，是許多人日常裡最熟悉的味道。我們希望守住這份傳承三代的溫暖，
        同時也讓傳統品牌透過新的視覺、包裝與數位行銷，用更貼近現代生活的樣貌被看見。
      </p>
      <p>
        從品牌文化、視覺設計到數位行銷，永和豆漿公館店正在探索品牌轉型的新方向。
      </p>
    </div>
  </div>
</section>

<!-- ===== 品牌理念 ===== -->
<section id="philosophy" class="about-section about-alt">
  <div class="section-title">
    <h2>品牌理念</h2>
    <small>Our Philosophy</small>
  </div>

  <div class="card-row">
    <div class="about-card">
      <div class="about-icon">🌾</div>
      <h4>用心選料</h4>
      <p>堅持挑選好原料，讓每一口都吃得安心，也吃得出食材本身的香氣。</p>
    </div>
    <div class="about-card">
      <div class="about-icon">🥛</div>
      <h4>傳統工法</h4>
      <p>延續傳承三代的熬煮方式，不趕時間，只為煮出最純粹濃厚的豆香。</p>
    </div>
    <div class="about-card">
      <div class="about-icon">🎁</div>
      <h4>傳遞心意</h4>
      <p>從日常早餐到質感禮盒，希望把這份溫暖，送給每一位重要的人。</p>
    </div>
  </div>
</section>

<!-- ===== 品牌特色 ===== -->
<section id="features" class="about-section">
  <div class="section-title">
    <h2>品牌特色</h2>
    <small>Our Features</small>
  </div>

  <div class="feature-grid">
    <div class="feature-item">
      <span class="feature-num">01</span>
      <h4>傳統與創新並存</h4>
      <p>保留經典台式早餐的味道，並以全新的視覺識別讓品牌更有記憶點。</p>
    </div>
    <div class="feature-item">
      <span class="feature-num">02</span>
      <h4>質感伴手禮</h4>
      <p>多元風味夾心餅、芋頭酥與果乾禮盒，送禮大方又體面。</p>
    </div>
    <div class="feature-item">
      <span class="feature-num">03</span>
      <h4>在地生活連結</h4>
      <p>以永和豆漿公館店為起點，探索台灣早餐文化，與街坊鄰居一起創造日常的美好。</p>
    </div>
    <div class="feature-item">
      <span class="feature-num">04</span>
      <h4>線上輕鬆訂購</h4>
      <p>結合網站與社群，隨時瀏覽商品、加入購物清單，輕鬆完成訂購。</p>
    </div>
  </div>
</section>

<!-- ===== 門市資訊 ===== -->
<section class="about-section about-alt">
  <div class="section-title">
    <h2>門市資訊</h2>
    <small>Visit Us</small>
  </div>

  <div class="info-box">
    <p>🕒 營業時間　06:00 - 12:00</p>
    <p>
      <a href="https://www.google.com/maps/search/?api=1&query=%E5%8F%B0%E5%8C%97%E5%B8%82%E4%B8%AD%E6%AD%A3%E5%8D%80%E7%BE%85%E6%96%AF%E7%A6%8F%E8%B7%AF%E4%B8%89%E6%AE%B5240%E5%B7%B711%E8%99%9F"
         target="_blank" rel="noopener noreferrer">📍 台北市中正區羅斯福路三段240巷11號</a>
    </p>
    <p><a href="tel:0223643733">📞 0223643733</a></p>
    <a href="products.php" class="about-btn">逛逛商品 ›</a>
  </div>
</section>

<!-- ===== 購物車彈窗（script.js 會用到）===== -->
<div class="cart-modal">
  <div class="cart-box"></div>
</div>

<section class="footer-mascot">
  <div class="mascot-wrap">
    <img class="mascot-img" src="99.png" alt="永和豆漿吉祥物">
    <div class="mascot-text">
      <p>用心熬煮每一碗豆漿</p>
      <p>傳承三代的溫暖早餐</p>
    </div>
  </div>
</section>
<!-- ===== Footer ===== -->
<footer class="yonghe-footer">
  <div class="footer-container">
    <div class="footer-col">
      <div class="brand-logo">永和豆漿</div>
      <h2>好味道的背後，是每一份認真。</h2>
      <h3>從品牌文化、視覺設計到數位行銷，永和豆漿公館店探索品牌轉型的新方向</h3>
    </div>

    <div class="footer-col">
      <h4>聯絡我們</h4>
      <p><a href="tel:0223643733">📞 0223643733</a></p>
      <p>
        <a href="https://www.google.com/maps/search/?api=1&query=%E5%8F%B0%E5%8C%97%E5%B8%82%E4%B8%AD%E6%AD%A3%E5%8D%80%E7%BE%85%E6%96%AF%E7%A6%8F%E8%B7%AF%E4%B8%89%E6%AE%B5240%E5%B7%B711%E8%99%9F"
           target="_blank" rel="noopener noreferrer">📍 台北市中正區羅斯福路三段240巷11號</a>
      </p>
      <p>🕒 營業時間 06:00 - 12:00</p>
    </div>

    <div class="footer-col">
      <h4>快速連結</h4>
      <div class="link-group">
        <a href="index.php">首頁</a>
        <br>
        <a href="about.php">關於我們</a>
        <br>
        <a href="https://www.instagram.com/jd.122630/">最新活動</a>
        <br>
        <a href="products.php">商品總覽</a>
      </div>
    </div>

    <div class="footer-col">
      <h4>追蹤我們</h4>
      <div class="social-icon">
        <a href="https://www.instagram.com/jd.122630/">IG</a>
        <a href="https://www.facebook.com/profile.php?id=61575355393606&locale=zh_TW">FB</a>
        <a href="https://line.me/R/ti/p/@725woshu">LINE</a>
      </div>
      <p class="social-desc">一起分享美味的日常 ❤</p>
    </div>
  </div>

  <div class="copyright">
    <p>© 2026 永和豆漿 版權所有 ・ 用心製作・只為給你最好的早餐</p>
  </div>
</footer>

<script src="script.js"></script>
</body>
</html>