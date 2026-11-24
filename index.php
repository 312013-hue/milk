<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>永和豆漿｜首頁</title>
<link rel="stylesheet" href="style.css">
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
   <div class="hero-row">
     <div class="logo">
    <img src="88.png" alt="LOGO">
  </div>

<div class="banner">
  <div class="banner-wrap">
    <!-- 💡 分身：最後一張 -->
    <div class="banner-item">
      <img src="3.jpeg" alt="圖片 3 分身">
      <div class="banner-text">
        <h1>芋香雅敘</h1>
        <h2>千層酥皮，飽滿芋心</h2>
        <h3>流心、麻糬、原味三種風味，經典台式伴手禮</h3>
        <a href="products.php#gift" class="banner-more">了解詳情 ›</a>
      </div>
    </div>
    <!-- 真實三張 -->
    <div class="banner-item">
      <img src="1.jpeg" alt="圖片 1">
      <div class="banner-text">
        <h1>茶敘時光</h1>
        <h2>酥香伴茶，靜享閒時</h2>
        <h3>多元風味夾心餅，質感禮盒，送禮大方</h3>
        <a href="products.php#gift" class="banner-more">了解詳情 ›</a>
      </div>
    </div>
    <div class="banner-item">
      <img src="2.jpeg" alt="圖片 2">
      <div class="banner-text">
        <h1>夏日果語</h1>
        <h2>一袋陽光果味，來自台灣土地</h2>
        <h3>芭樂｜金煌芒果｜小番茄，無添加果乾禮盒</h3>
        <a href="products.php#fruit" class="banner-more">了解詳情 ›</a>
      </div>
    </div>
    <div class="banner-item">
      <img src="3.jpeg" alt="圖片 3">
      <div class="banner-text">
        <h1>芋香雅敘</h1>
        <h2>千層酥皮，飽滿芋心</h2>
        <h3>流心、麻糬、原味三種風味，經典台式伴手禮</h3>
        <a href="products.php#gift" class="banner-more">了解詳情 ›</a>
      </div>
    </div>
    <!-- 💡 分身：第一張 -->
    <div class="banner-item">
      <img src="1.jpeg" alt="圖片 1 分身">
      <div class="banner-text">
        <h1>茶敘時光</h1>
        <h2>酥香伴茶，靜享閒時</h2>
        <h3>多元風味夾心餅，質感禮盒，送禮大方</h3>
        <a href="products.php#gift" class="banner-more">了解詳情 ›</a>
      </div>
    </div>
  </div>
  </div>

  <!-- 左右按鈕 -->
  <button class="prev-btn" aria-label="上一張">◀</button>
  <button class="next-btn" aria-label="下一張">▶</button>

  <!-- 圓點 -->
  <div class="banner-dots">
    <span class="dot active" data-index="0"></span>
    <span class="dot" data-index="1"></span>
    <span class="dot" data-index="2"></span>
  </div>
</div>

<section>
  <div class="section-title">
    <h2>最新消息</h2>
    <small>從傳統早餐出發，重新發現日常裡的美好</small>
  </div>
  <div class="news-row">
    <div class="news-card">
      <img src="8.jpg">
      <div class="txt">
        <h4>從一份早餐開始的故事</h4>
        <p>以永和豆漿公館店為起點，探索台灣早餐文化與在地生活</p>
      </div>
    </div>
    <div class="news-card">
      <img src="9.jpg">
      <div class="txt">
        <h4>傳統品牌，也能有新面貌</h4>
        <p>透過視覺識別與包裝設計，讓熟悉的品牌呈現更有記憶點的樣貌</p>
      </div>
    </div>
    <div class="news-card">
      <img src="10.jpg">
      <div class="txt">
        <h4>讓好味道被更多人看見</h4>
        <p>結合網站與社群行銷，讓品牌故事更容易傳遞給年輕世代</p>
      </div>
    </div>
  </div>
</section>

<section class="product-section">
  <div class="section-title">
    <h2>推薦商品</h2>
    <small>人氣早餐組合，顧客最愛</small>
  </div>
  <div class="product-slider">
    <button class="prod-prev">◂</button>
    <div class="prod-wrap">
      <!-- 商品卡片 -->
      <div class="prod-item">
        <div class="prod-img"><img src="4.jpg" alt=""></div>
        <h4>爵翠精緻禮盒</h4>
        <div class="price">NT$ 280</div>
        <button class="add-cart" onclick="addCart('爵翠精緻禮盒',280)">加入購物清單</button>
      </div>
      <div class="prod-item">
        <div class="prod-img"><img src="5.jpg" alt=""></div>
        <h4>爵翠嚐鮮禮袋</h4>
        <div class="price">NT$ 180</div>
        <button class="add-cart" onclick="addCart('爵翠嚐鮮禮袋',180)">加入購物清單</button>
      </div>
      <div class="prod-item">
        <div class="prod-img"><img src="6.jpg" alt=""></div>
        <h4>爵翠精品禮盒</h4>
        <div class="price">NT$ 380</div>
        <button class="add-cart" onclick="addCart('爵翠精品禮盒',380)">加入購物清單</button>
      </div>   <!-- ← 補上這個，同時順便修正商品名稱 -->

      <div class="prod-item">
    <div class="prod-img"><img src="7.jpg" alt=""></div>
      <h4>爵翠芋頭酥（三種口味）</h4>
      <select class="spec-select" aria-label="選擇規格">
        <option value="三入組" data-price="175">三入組</option>
        <option value="六入組" data-price="350">六入組</option>
      </select>
  <div class="price">NT$ 175</div>
  <button class="add-cart" onclick="addCartSpec(this, '爵翠芋頭酥')">加入購物清單</button>
</div>
    </div>
    <button class="prod-next">▸</button>
  </div>
</section>


<div class="cart-modal">
  <div class="cart-box"></div>
</div>

<!-- ========== Footer上方：吉祥物動畫區 ========== -->
<section class="footer-mascot">
  <div class="mascot-wrap">
    <!-- 替換成你的吉祥物圖片，建議透明PNG -->
    <img class="mascot-img" src="99.png" alt="永和豆漿吉祥物">
    <div class="mascot-text">
      <p>用心熬煮每一碗豆漿</p>
      <p>傳承三代的溫暖早餐</p>
    </div>
  </div>
</section>
<!-- ========== Footer 頁尾 ========== -->
<footer class="yonghe-footer">
  <div class="footer-container">
    <!-- 品牌 -->
    <div class="footer-col" >
      <div class="brand-logo">永和豆漿</div>
      <h2>好味道的背後，是每一份認真。</h2>
      <h3>從品牌文化、視覺設計到數位行銷，永和豆漿公館店探索品牌轉型的新方向</h3>
    </div>

    <!-- 聯絡我們 -->
<div class="footer-col">
  <h4>聯絡我們</h4>
  <p>
    <a href="tel:0223643733">📞 0223643733</a>
  </p>
  <p>
    <a href="https://www.google.com/maps/place/%E6%B0%B8%E5%92%8C%E8%B1%86%E6%BC%BF%EF%BC%88%E8%87%BA%E5%A4%A7%E5%85%AC%E9%A4%A8%E5%BA%97%EF%BC%89/@25.0177882,121.5299181,17z/data=!3m1!4b1!4m6!3m5!1s0x3442a98dfa4205dd:0xd6ea3f203fc39e0a!8m2!3d25.0177882!4d121.5299181!16s%2Fg%2F11vd406gp0?entry=ttu&g_ep=EgoyMDI2MTAyMC4wIKXMDSoASAFQAw%3D%3D"
       target="_blank" rel="noopener noreferrer">
      📍 台北市中正區羅斯福路三段240巷11號
    </a>
  </p>
  <p>🕒 營業時間 06:00 - 12:00</p>
</div>

    <!-- 快速連結 -->
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

    <!-- 追蹤我們 -->
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

  <!-- 版權列 -->
  <div class="copyright">
    <p>© 2026 永和豆漿 版權所有 ・ 用心製作・只為給你最好的早餐</p>
  </div>
</footer>
<script src="script.js"></script>
</body>
</html>
