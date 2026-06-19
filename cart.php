<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<main class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-6xl mx-auto">
        <header class="mb-12" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-2 text-sm">Giỏ hàng của bạn</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Thanh toán xanh</h1>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Cart Items List -->
            <div class="lg:col-span-2 space-y-6" id="cart-items-container">
                <!-- Items will be injected by JS -->
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 shadow-sm" id="empty-cart-msg">
                    <span class="material-symbols-outlined text-6xl text-slate-300 mb-6 font-light">shopping_basket</span>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Giỏ hàng trống</h3>
                    <p class="text-slate-500 font-medium mb-8 text-lg">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
                    <a href="rewards.php" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-slate-800 transition-all shadow-md">Tiếp tục mua sắm</a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm sticky top-28" data-aos="fade-left">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight mb-6 border-b border-slate-100 pb-4">Tóm tắt đơn hàng</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-slate-500 font-medium text-sm">
                            <span>Tổng tiền hàng</span>
                            <span id="subtotal" class="font-semibold text-slate-900">0 VNĐ</span>
                        </div>
                        <div class="flex justify-between text-slate-500 font-medium text-sm">
                            <span>Phí vận chuyển</span>
                            <span class="text-green-600 font-semibold">Miễn phí</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-2">
                            <span class="text-base font-bold text-slate-900">Thanh toán</span>
                            <span id="total-price" class="text-2xl font-extrabold text-slate-900 tracking-tight">0 VNĐ</span>
                        </div>
                    </div>

                    <!-- Points Discount Section -->
                    <div class="bg-green-50 rounded-2xl p-5 mb-8 border border-green-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-green-600 text-lg">stars</span>
                            <span class="text-sm font-semibold text-slate-900">Sử dụng điểm giảm giá</span>
                        </div>
                        <div class="flex gap-2">
                            <input type="number" id="points-to-use" placeholder="Số điểm dùng..." 
                                   class="w-full px-4 py-3 bg-white rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 outline-none font-medium text-sm text-slate-900 transition-all">
                            <button id="apply-pts" class="bg-slate-900 text-white px-4 py-3 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all">Áp dụng</button>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-3 font-medium">* Quy đổi: 1 PTS = <?= number_format(PTS_TO_VND) ?> VNĐ giảm giá</p>
                    </div>

                    <button id="checkout-btn" class="w-full py-4 bg-green-500 text-slate-900 rounded-xl font-bold text-base hover:bg-green-400 transition-all shadow-md">
                        Xác nhận đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cart = [];
    if (window.isLoggedIn && window.initialDbCart !== null) {
        cart = window.initialDbCart;
        localStorage.setItem('green_cart', JSON.stringify(cart));
    } else {
        cart = JSON.parse(localStorage.getItem('green_cart')) || [];
    }
    const container = document.getElementById('cart-items-container');
    const badge = document.getElementById('cart-badge');

    function updateBadge() {
        if(cart.length > 0) {
            badge.innerText = cart.length;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    function renderCart() {
        if (cart.length === 0) {
            container.innerHTML = `
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-100 shadow-sm" id="empty-cart-msg">
                    <span class="material-symbols-outlined text-6xl text-slate-300 mb-6 font-light">shopping_basket</span>
                    <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Giỏ hàng trống</h3>
                    <p class="text-slate-500 font-medium mb-8 text-lg">Bạn chưa thêm sản phẩm nào vào giỏ hàng.</p>
                    <a href="rewards.php" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold hover:bg-slate-800 transition-all shadow-md">Tiếp tục mua sắm</a>
                </div>
            `;
            updatePrices(0);
            return;
        }

        let html = '';
        let subtotal = 0;
        cart.forEach((item, index) => {
            const price = item.price_vnd !== undefined ? parseInt(item.price_vnd) : (item.price ? parseInt(String(item.price).replace(/\./g, '')) : 0);
            subtotal += price;
            html += `
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="${index * 50}">
                    <div class="flex items-center gap-5">
                        <div class="w-24 h-24 bg-slate-50 rounded-xl overflow-hidden shadow-inner shrink-0">
                            <img src="${item.image}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 mb-1 leading-tight">${item.name}</h3>
                            <p class="text-sm font-extrabold text-green-600">${price.toLocaleString('vi-VN')} VNĐ</p>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${index})" class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all" title="Xóa">
                        <span class="material-symbols-outlined text-xl">delete</span>
                    </button>
                </div>
            `;
        });
        container.innerHTML = html;
        updatePrices(subtotal);
    }

    function updatePrices(subtotal) {
        document.getElementById('subtotal').innerText = subtotal.toLocaleString('vi-VN') + ' VNĐ';
        document.getElementById('total-price').innerText = subtotal.toLocaleString('vi-VN') + ' VNĐ';
    }

    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        localStorage.setItem('green_cart', JSON.stringify(cart));
        if (window.isLoggedIn && window.saveCartToDB) window.saveCartToDB(cart);
        updateBadge();
        renderCart();
    }

    updateBadge();
    renderCart();

    // Points Logic
    document.getElementById('apply-pts').addEventListener('click', function() {
        const pts = parseInt(document.getElementById('points-to-use').value) || 0;
        let subtotal = 0;
        cart.forEach(item => {
            const price = item.price_vnd !== undefined ? parseInt(item.price_vnd) : (item.price ? parseInt(String(item.price).replace(/\./g, '')) : 0);
            subtotal += price;
        });
        
        const discount = pts * <?= PTS_TO_VND ?>;
        const total = Math.max(0, subtotal - discount);
        
        document.getElementById('total-price').innerText = total.toLocaleString('vi-VN') + ' VNĐ';
        alert(`Đã áp dụng giảm giá ${discount.toLocaleString('vi-VN')} VNĐ từ ${pts} điểm!`);
    });

    document.getElementById('checkout-btn').addEventListener('click', function() {
        if (!window.isLoggedIn) {
            alert('Vui lòng đăng nhập để thực hiện thanh toán!');
            window.location.href = 'auth/login.php';
            return;
        }
        if(cart.length === 0) return alert('Giỏ hàng trống!');
        
        const btn = this;
        const ogText = btn.innerText;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin align-middle mr-2 text-sm">sync</span> Đang xử lý...';
        btn.disabled = true;

        setTimeout(() => {
            alert('Cảm ơn bạn! Đơn hàng đang được xử lý.');
            localStorage.removeItem('green_cart');
            window.location.href = 'dashboard.php?success=order_placed';
        }, 800);
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
