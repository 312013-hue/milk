// ===== 設定
const CHECKOUT_URL = "checkout.php"; // 之後改成你的結帳頁

// ===== 購物車資料
let cart = [];
try {
    const saved = JSON.parse(localStorage.getItem("cart"));
    if (Array.isArray(saved)) cart = saved;
} catch (e) {
    cart = [];
}

function saveCart() {
    try {
        localStorage.setItem("cart", JSON.stringify(cart));
    } catch (e) {}
    updateCartCount();
}

function updateCartCount() {
    const countEl = document.getElementById("cart-count");
    if (countEl) {
        countEl.textContent = cart.reduce((sum, item) => sum + item.qty, 0);
    }
}

// 避免商品名稱破壞 HTML(XSS)
function escapeHTML(str) {
    return String(str).replace(/[&<>"']/g, c => ({
        "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
    }[c]));
}

// ===== 加入購物車
function addCart(name, price) {
    const found = cart.find(x => x.name === name);
    if (found) {
        found.qty += 1;
    } else {
        cart.push({ name, price: Number(price), qty: 1 });
    }
    saveCart();
    showToast(name + " 已加入購物清單");
}

// ===== 加入購物車（有規格的商品，例如芋頭酥）
function addCartSpec(btn, baseName) {
    const card = btn.closest(".prod-item");
    const select = card.querySelector(".spec-select");
    const opt = select.options[select.selectedIndex];
    addCart(`${baseName}（${opt.value}）`, Number(opt.dataset.price));
}

// 切換規格時，更新卡片上的價格
document.addEventListener("change", e => {
    if (!e.target.classList || !e.target.classList.contains("spec-select")) return;
    const opt = e.target.options[e.target.selectedIndex];
    const priceEl = e.target.closest(".prod-item").querySelector(".price");
    if (priceEl) priceEl.textContent = "NT$ " + opt.dataset.price;
});

function showToast(msg) {
    let toast = document.getElementById("cart-toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "cart-toast";
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add("show");
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => toast.classList.remove("show"), 1500);
}

// ===== 購物車彈窗
function ensureModal() {
    let modal = document.querySelector(".cart-modal");
    if (!modal) {
        modal = document.createElement("div");
        modal.className = "cart-modal";
        modal.innerHTML = '<div class="cart-box"></div>';
        document.body.appendChild(modal);
    }
    return modal;
}

function openCartModal() {
    ensureModal().classList.add("open");
    renderCartModal();
}

function closeCartModal() {
    const modal = document.querySelector(".cart-modal");
    if (modal) modal.classList.remove("open");
}

function changeQty(index, delta) {
    const item = cart[index];
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) cart.splice(index, 1);
    saveCart();
    renderCartModal();
}

function renderCartModal() {
    const box = document.querySelector(".cart-box");
    if (!box) return;

    let html = "";
    if (cart.length === 0) {
        html = "<p style='text-align:center;padding:20px'>購物車是空的</p>";
    } else {
        cart.forEach((i, idx) => {
            html += `
            <div class="cart-item">
                <div>${escapeHTML(i.name)} NT$${i.price}</div>
                <div class="qty-group">
                    <button class="qty-btn" data-index="${idx}" data-delta="-1">-</button>
                    <span>${i.qty}</span>
                    <button class="qty-btn" data-index="${idx}" data-delta="1">+</button>
                </div>
            </div>`;
        });
        const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
        html += `<div class="cart-total">總金額:NT$ ${total}</div>`;
    }

    html += `
    <div class="cart-buttons">
        <button class="btn-close" data-action="close">關閉</button>
        <button class="btn-checkout" data-action="checkout" ${cart.length === 0 ? "disabled" : ""}>前往結帳</button>
    </div>`;
    box.innerHTML = html;
}

function goCheckout() {
    closeCartModal();
    window.location.href = CHECKOUT_URL;
}

// ===== 事件綁定(事件委派)
document.addEventListener("click", e => {
    if (e.target.closest("#cart-link")) {
        e.preventDefault();
        openCartModal();
        return;
    }
    if (e.target.classList && e.target.classList.contains("cart-modal")) {
        closeCartModal();
        return;
    }
    const qtyBtn = e.target.closest(".qty-btn");
    if (qtyBtn) {
        changeQty(Number(qtyBtn.dataset.index), Number(qtyBtn.dataset.delta));
        return;
    }
    const actionBtn = e.target.closest("[data-action]");
    if (actionBtn) {
        if (actionBtn.dataset.action === "close") closeCartModal();
        if (actionBtn.dataset.action === "checkout") goCheckout();
    }
});

document.addEventListener("keydown", e => {
    if (e.key === "Escape") closeCartModal();
});

document.addEventListener("DOMContentLoaded", updateCartCount);

// ===== Banner 無縫自動輪播（左右按鈕 + 圓點）
function initBanner() {
    const bannerEl = document.querySelector(".banner");
    const wrap = document.querySelector(".banner-wrap");
    const dots = document.querySelectorAll(".dot");
    const prevBtn = document.querySelector(".prev-btn");
    const nextBtn = document.querySelector(".next-btn");
    const totalReal = dots.length; // 真實3張

    if (!wrap || !bannerEl || totalReal === 0) return;

    let bannerIndex = 1;       // 初始停在真圖1
    let bannerTimer = null;
    let isAnimating = false;   // 動畫鎖，避免連點跑出範圍

    const slidePercent = 100 / 5; // 5個item，每個20%

    function updateDots() {
        let dotIndex;
        if (bannerIndex === 1) dotIndex = 0;
        else if (bannerIndex === 2) dotIndex = 1;
        else if (bannerIndex === 3) dotIndex = 2;
        else if (bannerIndex === 4) dotIndex = 0; // 假1
        else dotIndex = 2;                        // 假3 (index 0)
        dots.forEach((dot, i) => dot.classList.toggle("active", i === dotIndex));
    }

    function go(idx, hasAnimation = true) {
        if (hasAnimation && isAnimating) return;

        bannerIndex = idx;

        if (hasAnimation) {
            isAnimating = true;
            wrap.style.transition = "transform 0.5s ease-in-out";
        } else {
            wrap.style.transition = "none";
        }
        wrap.style.transform = `translate3d(-${bannerIndex * slidePercent}%, 0, 0)`;
        void wrap.offsetWidth;   // 強制瀏覽器套用，避免瞬間跳位置被吃掉
        updateDots();
    }

    wrap.addEventListener("transitionend", (e) => {
        if (e.propertyName !== "transform") return;
        isAnimating = false;

        if (bannerIndex === 4) {
            go(1, false);
        } else if (bannerIndex === 0) {
            go(3, false);
        }
    });

    function stopAutoPlay() {
        if (bannerTimer) {
            clearInterval(bannerTimer);
            bannerTimer = null;
        }
    }

    function startAutoPlay() {
        stopAutoPlay();
        bannerTimer = setInterval(() => {
            go(bannerIndex + 1);
        }, 4000);
    }

    function resetBanner() {
        isAnimating = false;
        if (bannerIndex === 4) bannerIndex = 1;
        else if (bannerIndex === 0) bannerIndex = 3;
        go(bannerIndex, false);
        startAutoPlay();
    }

    bannerEl.addEventListener("mouseenter", stopAutoPlay);
    bannerEl.addEventListener("mouseleave", startAutoPlay);

    dots.forEach((dot, i) => {
        dot.addEventListener("click", () => {
            stopAutoPlay();
            go(i + 1);
            startAutoPlay();
        });
    });

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            stopAutoPlay();
            go(bannerIndex - 1);
            startAutoPlay();
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            stopAutoPlay();
            go(bannerIndex + 1);
            startAutoPlay();
        });
    }

    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            stopAutoPlay();
        } else {
            resetBanner();
        }
    });

    window.addEventListener("pageshow", (e) => {
        if (e.persisted) resetBanner();
    });

    go(bannerIndex, false);
    startAutoPlay();
}

document.addEventListener("DOMContentLoaded", initBanner);

// ===== 圈型輪播（推薦商品）=====
function initProductCarousel() {
    const items   = document.querySelectorAll(".prod-item");
    const slider  = document.querySelector(".product-slider");
    const prevBtn = document.querySelector(".prod-prev");
    const nextBtn = document.querySelector(".prod-next");

    // 沒有輪播的頁面（例如結帳頁）直接跳過，避免出錯
    if (!slider || !prevBtn || !nextBtn || items.length === 0) return;

    const total = items.length;
    const half  = Math.floor(total / 2);
    let current = 0;
    let timer   = null;
    let isHovering = false;

    // 距離中間幾張：位置、縮放、透明度
    const config = { maxShow: 1, x: [0, 440], scale: [1.25, 0.9], opacity: [1, 0.75] };

    function render() {
        items.forEach((item, i) => {
            let d = ((i - current + total + half) % total) - half;
            const abs  = Math.abs(d);
            const sign = d < 0 ? -1 : 1;

            if (abs <= config.maxShow) {
                item.style.transform = `translateX(${sign * config.x[abs]}px) scale(${config.scale[abs]})`;
                item.style.opacity   = config.opacity[abs];
                item.style.zIndex    = 10 - abs;
                item.classList.add("show");
            } else {
                const last = config.maxShow;
                item.style.transform = `translateX(${sign * (config.x[last] + 200)}px) scale(0.5)`;
                item.style.opacity   = 0;
                item.style.zIndex    = 0;
                item.classList.remove("show");
            }
            item.classList.toggle("is-center", d === 0);
        });
    }

    function goNext() { current = (current + 1) % total; render(); }
    function goPrev() { current = (current - 1 + total) % total; render(); }

    // 自動輪播（滑鼠移上去暫停）
    function stopAuto() {
        clearInterval(timer);
        timer = null;
    }

    function startAuto() {
        stopAuto();                       // 先清掉舊的，確保只有一個計時器
        timer = setInterval(goNext, 3000);
    }

    function restartAuto() {
        stopAuto();
        if (!isHovering) startAuto();     // 滑鼠還在上面就維持暫停
    }

    nextBtn.onclick = () => { goNext(); restartAuto(); };
    prevBtn.onclick = () => { goPrev(); restartAuto(); };

    // 點擊側邊的卡片，直接把它移到中間（按鈕和下拉選單除外）
    items.forEach((item, i) => {
        item.addEventListener("click", e => {
            if (i !== current && !e.target.closest(".add-cart, .spec-select")) {
                current = i;
                render();
                restartAuto();
            }
        });
    });

    slider.addEventListener("mouseenter", () => {
        isHovering = true;
        stopAuto();
    });
    slider.addEventListener("mouseleave", () => {
        isHovering = false;
        startAuto();
    });

    // 切到別的分頁時暫停，回來再繼續
    document.addEventListener("visibilitychange", () => {
        if (document.hidden) stopAuto();
        else if (!isHovering) startAuto();
    });

    render();
    startAuto();
}

document.addEventListener("DOMContentLoaded", initProductCarousel);

// ===== 產品頁分類切換
function switchCategory(cate) {
    const cards = document.querySelectorAll(".product-grid .product-card");
    const tabs = document.querySelectorAll(".tab");
    tabs.forEach(t => t.classList.toggle("active", t.dataset.cate === cate));
    cards.forEach(card => {
        card.style.display = (cate === "all" || card.dataset.cate === cate) ? "block" : "none";
    });
}

// ===== 結帳送出訂單
function submitOrder() {
    const name = document.getElementById("buyer-name").value.trim();
    const phone = document.getElementById("buyer-phone").value.trim();
    const phoneReg = /^09[0-9]{8}$/;
    if (!name) {
        alert("請填寫姓名");
        return;
    }
    if (!phoneReg.test(phone)) {
        alert("請輸入正確手機號碼(09開頭10位數)");
        return;
    }
    if (cart.length === 0) {
        alert("購物車要有商品");
        return;
    }
    const orders = JSON.parse(localStorage.getItem("orders")) || [];
    const orderNo = "ORD" + Date.now();
    orders.push({
        id: orderNo,
        name,
        phone,
        items: [...cart],
        time: new Date().toLocaleString(),
        total: cart.reduce((s, i) => s + i.price * i.qty, 0)
    });
    localStorage.setItem("orders", JSON.stringify(orders));
    cart = [];
    saveCart();
    alert(`訂單送出成功！訂單編號：${orderNo}`);
    location.reload();
}

// 刪除訂單
function deleteOrder(orderId) {
    if (!confirm("確定刪除此訂單?")) return;
    let orders = JSON.parse(localStorage.getItem("orders")) || [];
    orders = orders.filter(o => o.id !== orderId);
    localStorage.setItem("orders", JSON.stringify(orders));
    loadAdminOrder();
}

// ===== 後台載入訂單
function loadAdminOrder() {
    const wrap = document.getElementById("orderList");
    if (!wrap) return;
    const orders = JSON.parse(localStorage.getItem("orders")) || [];
    if (orders.length === 0) {
        wrap.innerHTML = "<tr><td colspan='6'>尚無訂單</td></tr>";
        return;
    }
    let html = "";
    orders.forEach(o => {
        const itemText = o.items.map(i => `${escapeHTML(i.name)} x${i.qty}`).join("<br>");
        html += `
        <tr>
            <td>${escapeHTML(o.id)}</td>
            <td>${escapeHTML(o.name)}</td>
            <td>${escapeHTML(o.phone)}</td>
            <td>${itemText}</td>
            <td>${escapeHTML(o.time)}</td>
            <td><button class="del-order" onclick="deleteOrder('${escapeHTML(o.id)}')">刪除</button></td>
        </tr>
        `;
    });
    wrap.innerHTML = html;
}

// 頁面載入自動執行
window.onload = () => {
    updateCartCount();
    loadAdminOrder();
};