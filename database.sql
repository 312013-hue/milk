-- 永和豆漿後台資料庫（MySQL / MariaDB）
-- 用法：phpMyAdmin → 匯入 → 選此檔案。只需匯入一次。
CREATE DATABASE IF NOT EXISTS yonghe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE yonghe;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login DATETIME NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(20) NOT NULL UNIQUE,         -- 對應前台 #gift #fruit ...
  name VARCHAR(30) NOT NULL,
  name_en VARCHAR(40) NOT NULL DEFAULT '',
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL DEFAULT '',
  price INT UNSIGNED NOT NULL,                -- 有規格時＝最低價
  options JSON NULL,                          -- 規格，例：[{"label":"三入組","price":175}]
  tag VARCHAR(20) NOT NULL DEFAULT '',        -- 熱銷、新品…
  image VARCHAR(255) NOT NULL DEFAULT '',     -- 相對於網站根目錄，例：4.jpg 或 uploads/products/xxx.jpg
  is_active TINYINT(1) NOT NULL DEFAULT 1,    -- 1=上架 0=下架
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_prod_cat FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_no VARCHAR(30) NOT NULL UNIQUE,
  name VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  note VARCHAR(255) NOT NULL DEFAULT '',
  total INT UNSIGNED NOT NULL,
  status ENUM('pending','confirmed','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_created (created_at),
  INDEX idx_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,               -- 商品刪除後變 NULL，但名稱與價格仍保留
  name VARCHAR(100) NOT NULL,                 -- 下單當下的名稱（快照）
  price INT UNSIGNED NOT NULL,                -- 下單當下的單價（快照）
  qty INT UNSIGNED NOT NULL,
  CONSTRAINT fk_item_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_item_prod  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===== 初始分類 =====
INSERT IGNORE INTO categories (id, slug, name, name_en, sort_order) VALUES
 (1,'gift','禮盒','Gift Boxes',1),
 (2,'fruit','果乾','Dried Fruit',2),
 (3,'nougat','牛軋糖','Nougat',3),
 (4,'jerky','肉乾','Jerky',4),
 (5,'other','其他','More',5);

-- ===== 匯入前台現有商品（價格以卡片上顯示的為準）=====
INSERT INTO products (category_id, name, description, price, options, tag, image, sort_order) VALUES
 (1,'爵翠精緻禮盒','質感包裝，送禮大方。',280,NULL,'熱銷','4.jpg',1),
 (1,'爵翠嚐鮮禮袋','多種風味小份量，輕鬆嚐鮮。',180,NULL,'入門款','5.jpg',2),
 (1,'爵翠精品禮盒','綜合口味（伯爵/抹茶/鮮奶油/巧克力/花生芝麻）',380,NULL,'','6.jpg',3),
 (1,'爵翠芋頭酥（三種口味）','千層酥皮，飽滿芋心。',175,'[{"label":"三入組","price":175},{"label":"六入組","price":350}]','伴手禮','7.jpg',4),
 (1,'時光土鳳梨酥','時光土鳳梨酥8入',380,NULL,'新品','12.jpg',5),
 (1,'夏日果語','芭樂、金煌芒果、小番茄果乾，無添加。',480,NULL,'','13.jpg',6),
 (1,'豐饌之禮','綜合5種口味+綜合堅果1瓶+土鳳梨酥4入',680,NULL,'','14.jpg',7),
 (1,'養心之禮','綜合5口味+綜合堅果1罐',510,NULL,'節慶款','15.jpg',8),
 (2,'豆敘．番茄果乾','番茄果乾120g',135,NULL,'人氣','16.jpg',1),
 (2,'豆敘．芭樂果乾','芭樂果乾120g',135,NULL,'','17.jpg',2),
 (2,'豆敘．芒果果乾','金煌芒果乾120g',150,NULL,'新品','18.jpg',3),
 (3,'豆敘．蔓越莓牛軋糖','蔓越莓牛軋糖150g',150,NULL,'經典','19.jpg',1),
 (3,'豆敘．原味杏仁牛軋糖','原味牛軋糖150g',150,NULL,'','20.jpg',2),
 (4,'豆敘．泰式檸檬豬肉乾','泰式豬肉乾180g',220,NULL,'熱銷','21.jpg',1),
 (4,'豆敘．蜜汁豬肉乾','蜜汁豬肉乾180g',190,NULL,'','22.jpg',2),
 (5,'爵翠．綜合堅果','綜合6種堅果',230,NULL,'','23.jpg',1),
 (5,'豆敘．手工鹹蛋黃夾心酥餅','綜合5種口味6入',160,NULL,'','24.jpg',2),
 (5,'豆敘．杏仁堅果手工桃蘇','手工杏仁桃蘇10入',150,NULL,'伴手禮','25.jpg',3);

-- ===== （選用）測試訂單：想先看儀表板效果時，把下面整段取消註解再執行 =====
-- INSERT INTO orders (order_no,name,phone,total,status,created_at) VALUES
--  ('ORD-TEST-001','王小明','0912345678',460,'pending',NOW()),
--  ('ORD-TEST-002','林美玲','0922333444',680,'confirmed',NOW() - INTERVAL 1 DAY),
--  ('ORD-TEST-003','陳大文','0933111222',350,'completed',NOW() - INTERVAL 3 DAY);
-- INSERT INTO order_items (order_id,product_id,name,price,qty) VALUES
--  (1,1,'爵翠精緻禮盒',280,1),(1,2,'爵翠嚐鮮禮袋',180,1),
--  (2,7,'豐饌之禮',680,1),
--  (3,4,'爵翠芋頭酥（三種口味）（六入組）',350,1);
