<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>永和豆漿｜商品介紹</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+TC:wght@600;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="products.css">
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
      <a href="#gift">禮盒</a>
      <a href="#fruit">果乾</a>
      <a href="#nougat">牛軋糖</a>
      <a href="#jerky">肉乾</a>
      <a href="#other">其他</a>
    </div>
  </div>
    <a href="#" id="cart-link">購物車 (<span id="cart-count">0</span>)</a>
  </div>
</nav>

<!-- ========== 商品介紹 ========== -->
<header class="cat-head">
  <h1>商品介紹</h1>
  <p>禮盒、果乾、牛軋糖、肉乾，一次看完。</p>
</header>

<div class="cat-nav">
  <a href="#gift">禮盒</a>
  <a href="#fruit">果乾</a>
  <a href="#nougat">牛軋糖</a>
  <a href="#jerky">肉乾</a>
  <a href="#other">其他</a>
</div>

<main class="catalog">

  <!-- ===== 禮盒 ===== -->
  <div class="cat" id="gift" style="--c:#a8321f">
    <h2>禮盒<small>Gift Boxes</small></h2>
    <p class="lead">節慶、拜訪、謝禮，整盒包好直接送。</p>
    <div class="grid">
      <article class="pcard prod-item">
        <div class="pic"><img src="4.jpg" alt="爵翠精緻禮盒"></div>
        <div class="body">
          <div class="name">爵翠精緻禮盒</div>
          <div class="desc">質感包裝，送禮大方。</div>
          <div class="meta"><span class="price">NT$ 280</span><span class="tag">熱銷</span></div>
          <button class="add-cart" onclick="addCart('爵翠精緻禮盒',280)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="5.jpg" alt="爵翠嚐鮮禮袋"></div>
        <div class="body">
          <div class="name">爵翠嚐鮮禮袋</div>
          <div class="desc">多種風味小份量，輕鬆嚐鮮。</div>
          <div class="meta"><span class="price">NT$ 180</span><span class="tag">入門款</span></div>
          <button class="add-cart" onclick="addCart('爵翠嚐鮮禮袋',180)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="6.jpg" alt="爵翠精品禮盒"></div>
        <div class="body">
          <div class="name">爵翠精品禮盒</div>
          <div class="desc">綜合口味（伯爵/抹茶/鮮奶油/巧克力/花生芝麻）</div>
          <div class="meta"><span class="price">NT$ 380</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('爵翠精品禮盒',380)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="7.jpg" alt="爵翠芋頭酥"></div>
        <div class="body">
          <div class="name">爵翠芋頭酥（三種口味）</div>
          <div class="desc">千層酥皮，飽滿芋心。</div>
          <select class="spec-select" aria-label="選擇規格">
            <option value="三入組" data-price="175">三入組</option>
            <option value="六入組" data-price="350">六入組</option>
          </select>
          <div class="meta"><span class="price">NT$ 175</span><span class="tag">伴手禮</span></div>
          <button class="add-cart" onclick="addCartSpec(this, '爵翠芋頭酥')">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="12.jpg" alt="時光土鳳梨酥"></div>
        <div class="body">
          <div class="name">時光土鳳梨酥</div>
          <div class="desc">時光土鳳梨酥8入</div>
          <div class="meta"><span class="price">NT$ 380</span><span class="tag">新品</span></div>
          <button class="add-cart" onclick="addCart('時光土鳳梨酥8入',380)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="13.jpg" alt="夏日果語"></div>
        <div class="body">
          <div class="name">夏日果語</div>
          <div class="desc">芭樂、金煌芒果、小番茄果乾，無添加。</div>
          <div class="meta"><span class="price">NT$ 480</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('夏日果語',480)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="14.jpg" alt="豐饌之禮"></div>
        <div class="body">
          <div class="name">豐饌之禮</div>
          <div class="desc">綜合5種口味+綜合堅果1瓶+土鳳梨酥4入</div>
          <div class="meta"><span class="price">NT$ 680</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('豐饌之禮',680)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="15.jpg" alt="養心之禮"></div>
        <div class="body">
          <div class="name">養心之禮</div>
          <div class="desc">綜合5口味+綜合堅果1罐</div>
          <div class="meta"><span class="price">NT$ 510</span><span class="tag">節慶款</span></div>
          <button class="add-cart" onclick="addCart('養心之禮',510)">加入購物清單</button>
        </div>
      </article>
    </div>
  </div>

  <!-- ===== 果乾（範例，請換成實際商品） ===== -->
  <div class="cat" id="fruit" style="--c:#d98a0b">
    <h2>果乾<small>Dried Fruit</small></h2>
    <p class="lead">無添加烘製，保留台灣水果的原味。</p>
    <div class="grid">
      <article class="pcard prod-item">
        <div class="pic"><img src="16.jpg" alt="豆敘．番茄果乾"></div>
        <div class="body">
          <div class="name">豆敘．番茄果乾</div>
          <div class="desc">番茄果乾120g</div>
          <div class="meta"><span class="price">NT$ 135</span><span class="tag">人氣</span></div>
          <button class="add-cart" onclick="addCart('豆敘．番茄果乾',135)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="17.jpg" alt="豆敘．芭樂果乾"></div>
        <div class="body">
          <div class="name">豆敘．芭樂果乾</div>
          <div class="desc">芭樂果乾120g</div>
          <div class="meta"><span class="price">NT$ 135</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('豆敘．芭樂果乾',135)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="18.jpg" alt="豆敘．芒果果乾"></div>
        <div class="body">
          <div class="name">豆敘．芒果果乾</div>
          <div class="desc">金煌芒果乾120g</div>
          <div class="meta"><span class="price">NT$ 150</span><span class="tag">新品</span></div>
          <button class="add-cart" onclick="addCart('豆敘．芒果果乾',150)">加入購物清單</button>
        </div>
      </article>
    </div>
  </div>

  <!-- ===== 牛軋糖（範例） ===== -->
  <div class="cat" id="nougat" style="--c:#6f8f5f">
    <h2>牛軋糖<small>Nougat</small></h2>
    <p class="lead">奶香濃、不黏牙，一口接一口。</p>
    <div class="grid">
      <article class="pcard prod-item">
        <div class="pic"><img src="19.jpg" alt="豆敘．蔓越莓牛軋糖"></div>
        <div class="body">
          <div class="name">豆敘．蔓越莓牛軋糖</div>
          <div class="desc">蔓越莓牛軋糖150g</div>
          <div class="meta"><span class="price">NT$ 150</span><span class="tag">經典</span></div>
          <button class="add-cart" onclick="addCart('豆敘．蔓越莓牛軋糖',150)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="20.jpg" alt="豆敘．原味杏仁牛軋糖"></div>
        <div class="body">
          <div class="name">豆敘．原味杏仁牛軋糖</div>
          <div class="desc">原味牛軋糖150g</div>
          <div class="meta"><span class="price">NT$ 150</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('豆敘．原味杏仁牛軋糖',150)">加入購物清單</button>
        </div>
      </article>
    </div>
  </div>

  <!-- ===== 肉乾（範例） ===== -->
  <div class="cat" id="jerky" style="--c:#7a3b24">
    <h2>肉乾<small>Jerky</small></h2>
    <p class="lead">慢火烘烤，咬勁十足。</p>
    <div class="grid">
      <article class="pcard prod-item">
        <div class="pic"><img src="21.jpg" alt="豆敘．泰式檸檬豬肉乾"></div>
        <div class="body">
          <div class="name">豆敘．泰式檸檬豬肉乾</div>
          <div class="desc">泰式豬肉乾180g</div>
          <div class="meta"><span class="price">NT$ 220</span><span class="tag">熱銷</span></div>
          <button class="add-cart" onclick="addCart('豆敘．泰式檸檬豬肉乾',220)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="22.jpg" alt="豆敘．蜜汁豬肉乾"></div>
        <div class="body">
          <div class="name">豆敘．蜜汁豬肉乾</div>
          <div class="desc">蜜汁豬肉乾180g</div>
          <div class="meta"><span class="price">NT$ 190</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('豆敘．蜜汁豬肉乾',190)">加入購物清單</button>
        </div>
      </article>
    </div>
  </div>

  <!-- ===== 其他 ===== -->
  <div class="cat" id="other" style="--c:#4a6a86">
    <h2>其他<small>More</small></h2>
    <p class="lead">配茶配咖啡，順手加購。</p>
    <div class="grid">
      <article class="pcard prod-item">
        <div class="pic"><img src="23.jpg" alt="爵翠．綜合堅果"></div>
        <div class="body">
          <div class="name">爵翠．綜合堅果</div>
          <div class="desc">綜合6種堅果</div>
          <div class="meta"><span class="price">NT$ 230</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('爵翠．綜合堅果',230)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="24.jpg" alt="豆敘．手工鹹蛋黃夾心酥餅"></div>
        <div class="body">
          <div class="name">豆敘．手工鹹蛋黃夾心酥餅</div>
          <div class="desc">綜合5種口味6入</div>
          <div class="meta"><span class="price">NT$ 160</span><span class="tag"></span></div>
          <button class="add-cart" onclick="addCart('豆敘．手工鹹蛋黃夾心酥餅',160)">加入購物清單</button>
        </div>
      </article>
      <article class="pcard prod-item">
        <div class="pic"><img src="25.jpg" alt="豆敘．杏仁堅果手工桃蘇"></div>
        <div class="body">
          <div class="name">豆敘．杏仁堅果手工桃蘇</div>
          <div class="desc">手工杏仁桃蘇10入</div>
          <div class="meta"><span class="price">NT$ 150</span><span class="tag">伴手禮</span></div>
          <button class="add-cart" onclick="addCart('豆敘．杏仁堅果手工桃蘇',150)">加入購物清單</button>
        </div>
      </article>
    </div>
  </div>

</main>

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