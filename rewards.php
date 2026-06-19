<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Search and AI Section -->
<div class="min-h-screen bg-[#f8fafc] pt-32 pb-24 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-3 text-sm">🛒 Eco Marketplace</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Sản phẩm đổi quà</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">Sử dụng điểm Green Points để đổi lấy các sản phẩm thân thiện với môi trường. Hành động nhỏ, tác động lớn.</p>
        </div>

        <!-- Search Bar & AI Camera -->
        <div class="max-w-3xl mx-auto mb-16" data-aos="fade-up" data-aos-delay="100">
            <div class="relative group">
                <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-slate-400 group-focus-within:text-green-500 transition-colors">search</span>
                </div>
                <input type="text" id="productSearch" placeholder="Tìm kiếm sản phẩm sinh học..." 
                       class="w-full pl-16 pr-40 py-5 bg-white rounded-2xl border border-slate-200 shadow-sm text-slate-900 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all outline-none font-medium">
                
                <div class="absolute inset-y-2 right-2 flex gap-2">
                    <button id="aiSearchBtn" class="flex items-center gap-2 px-5 bg-slate-900 text-white rounded-xl font-semibold text-sm hover:bg-green-600 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                        Tải ảnh
                    </button>
                    <input type="file" id="aiImageInput" accept="image/*" class="hidden">
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-4 text-center">Mẹo: Quét hình ảnh thực tế của sản phẩm bằng AI để tìm kiếm nhanh chóng.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            <!-- Sidebar filters -->
            <div class="lg:col-span-1 space-y-8" data-aos="fade-right">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide mb-6 border-b border-slate-100 pb-4">Danh mục</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="flex justify-between items-center text-green-700 bg-green-50 px-4 py-3 rounded-xl font-medium transition-colors">
                                <span>Tất cả</span>
                                <span class="bg-green-200 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold">24</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex justify-between items-center text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl font-medium transition-colors">
                                <span>Ống hút sinh học</span>
                                <span class="bg-slate-100 text-slate-500 text-xs px-2 py-0.5 rounded-full font-bold">8</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex justify-between items-center text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl font-medium transition-colors">
                                <span>Voucher F&B</span>
                                <span class="bg-slate-100 text-slate-500 text-xs px-2 py-0.5 rounded-full font-bold">12</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex justify-between items-center text-slate-600 hover:bg-slate-50 px-4 py-3 rounded-xl font-medium transition-colors">
                                <span>Phụ kiện cá nhân</span>
                                <span class="bg-slate-100 text-slate-500 text-xs px-2 py-0.5 rounded-full font-bold">4</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Points Banner -->
                <div class="bg-slate-900 p-8 rounded-2xl text-white relative overflow-hidden shadow-lg shadow-slate-900/10">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/20 rounded-full blur-2xl"></div>
                    <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Số dư Green Points</h4>
                    <div class="text-4xl font-extrabold mb-6 tracking-tight">2,450 <span class="text-sm text-green-500 font-bold">PTS</span></div>
                    <button class="w-full py-3 bg-green-500 text-slate-900 rounded-xl text-sm font-bold hover:bg-green-400 transition-all shadow-sm">Nạp thêm điểm</button>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8" data-aos="fade-up">
                <?php
                // Tỷ lệ đổi điểm
                $pts_to_vnd = PTS_TO_VND;
                
                // Lấy sản phẩm từ database
                $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
                $products = $stmt->fetchAll();
                
                if (empty($products)): ?>
                    <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">inventory_2</span>
                        <p class="text-slate-500 font-semibold text-lg">Chưa có sản phẩm nào</p>
                    </div>
                <?php endif;

                foreach ($products as $p): ?>
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all group product-card overflow-hidden flex flex-col" data-category="<?= $p['category'] ?>" data-name="<?= mb_strtolower($p['name']) ?>">
                        <div class="aspect-square overflow-hidden relative bg-slate-50">
                            <a href="product_detail.php?id=<?= $p['id'] ?>">
                                <img src="<?= $p['image'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </a>
                            <div class="absolute top-4 left-4">
                                <span class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 shadow-sm"><?= $p['category'] ?></span>
                            </div>
                            <button onclick="addToCart(<?= htmlspecialchars(json_encode($p)) ?>)" 
                                    class="absolute bottom-4 right-4 w-12 h-12 bg-green-500 text-white rounded-xl flex items-center justify-center opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all shadow-lg hover:bg-green-600 z-10">
                                <span class="material-symbols-outlined font-bold">add_shopping_cart</span>
                            </button>
                        </div>
                        <div class="p-6 flex flex-col flex-1">
                            <a href="product_detail.php?id=<?= $p['id'] ?>" class="mb-4">
                                <h4 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 hover:text-green-600 transition-colors"><?= $p['name'] ?></h4>
                            </a>
                            <div class="mt-auto space-y-3">
                                <div class="flex justify-between items-center">
                                    <p class="text-xl font-extrabold text-slate-900 tracking-tight"><?= number_format($p['price_vnd']) ?> <span class="text-sm font-semibold text-slate-500">VNĐ</span></p>
                                    <button class="text-slate-300 hover:text-red-500 transition-colors"><span class="material-symbols-outlined text-[20px]">favorite</span></button>
                                </div>
                                <div class="bg-green-50 rounded-lg py-2 px-3 inline-flex items-center gap-2 border border-green-100">
                                    <span class="material-symbols-outlined text-[16px] text-green-600">redeem</span>
                                    <p class="text-xs font-semibold text-green-700">Dùng điểm giảm đến <?= number_format($p['max_pts'] * $pts_to_vnd) ?>đ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Floating Cart Button -->
<button onclick="toggleCart()" class="fixed bottom-8 right-8 w-16 h-16 bg-slate-900 text-white rounded-2xl shadow-xl flex items-center justify-center group hover:scale-105 hover:bg-slate-800 transition-all z-40">
    <span class="material-symbols-outlined text-[28px]">shopping_cart</span>
    <span id="cartCount" class="absolute -top-2 -right-2 w-7 h-7 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-md border-2 border-slate-900">0</span>
</button>

<!-- Cart Sidebar Overlay -->
<div id="cartOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] opacity-0 pointer-events-none transition-opacity duration-300"></div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-[101] translate-x-full transition-transform duration-300 shadow-2xl flex flex-col border-l border-slate-100">
    <div class="flex justify-between items-center p-6 border-b border-slate-100">
        <h2 class="text-xl font-bold text-slate-900">Giỏ hàng</h2>
        <button onclick="toggleCart()" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-slate-100 text-slate-500 transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <div id="cartItems" class="flex-1 overflow-y-auto p-6 space-y-4">
        <div class="text-center py-20 opacity-40">
            <span class="material-symbols-outlined text-6xl mb-4">remove_shopping_cart</span>
            <p class="font-semibold text-sm">Giỏ hàng trống</p>
        </div>
    </div>

    <div class="p-6 bg-slate-50 border-t border-slate-100 space-y-6">
        <!-- Point Usage Toggle -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-green-500 text-[20px]">eco</span>
                    <span class="text-sm font-semibold text-slate-900">Dùng Green Points</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="usePointsToggle" class="sr-only peer" onchange="updateCartUI()">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                </label>
            </div>
            <p id="pointInfo" class="text-xs text-slate-500">Bật để giảm giá trực tiếp vào tổng đơn hàng.</p>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between items-center text-sm font-medium text-slate-500">
                <span>Tạm tính</span>
                <span id="subtotalVnd" class="text-slate-900 font-bold">0đ</span>
            </div>
            <div id="discountRow" class="flex justify-between items-center text-sm font-medium hidden">
                <span class="text-green-600">Điểm giảm giá</span>
                <span id="discountVnd" class="text-green-600 font-bold">-0đ</span>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                <span class="text-base font-bold text-slate-900">Cần thanh toán</span>
                <span id="totalVnd" class="text-2xl font-extrabold text-slate-900 tracking-tight">0đ</span>
            </div>
        </div>

        <button onclick="checkout()" class="w-full py-4 bg-slate-900 text-white rounded-xl font-bold text-base hover:bg-slate-800 transition-all shadow-md">
            Thanh toán ngay
        </button>
    </div>
</div>

<!-- Logic Scripts -->
<script>
    let cart = [];
    if (window.isLoggedIn && window.initialDbCart !== null) {
        cart = window.initialDbCart;
        localStorage.setItem('green_cart', JSON.stringify(cart));
    } else {
        cart = JSON.parse(localStorage.getItem('green_cart')) || [];
    }
    const ptsToVnd = <?= PTS_TO_VND ?>; // Sử dụng constant từ backend
    let userPoints = <?= isset($header_user['points']) ? (int)$header_user['points'] : 0 ?>;

    function updateCartUI() {
        const cartItems = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const headerBadge = document.getElementById('cart-badge');
        const subtotalVnd = document.getElementById('subtotalVnd');
        const discountVnd = document.getElementById('discountVnd');
        const totalVnd = document.getElementById('totalVnd');
        const usePointsToggle = document.getElementById('usePointsToggle');
        const discountRow = document.getElementById('discountRow');
        const pointInfo = document.getElementById('pointInfo');
        
        cartCount.innerText = cart.length;
        if (headerBadge) {
            headerBadge.innerText = cart.length;
            if (cart.length > 0) headerBadge.classList.remove('hidden');
            else headerBadge.classList.add('hidden');
        }
        
        if (cart.length === 0) {
            cartItems.innerHTML = `<div class="text-center py-20 opacity-40"><span class="material-symbols-outlined text-6xl mb-4">remove_shopping_cart</span><p class="font-semibold text-sm">Giỏ hàng trống</p></div>`;
            subtotalVnd.innerText = "0đ";
            totalVnd.innerText = "0đ";
            discountRow.classList.add('hidden');
            return;
        }

        let subtotal = 0;
        let maxPtsCanUse = 0;

        cartItems.innerHTML = cart.map((item, index) => {
            subtotal += item.price_vnd;
            maxPtsCanUse += item.max_pts;
            return `
                <div class="flex gap-4 bg-white p-3 rounded-xl border border-slate-100 shadow-sm group">
                    <img src="${item.image}" class="w-20 h-20 object-cover rounded-lg bg-slate-50">
                    <div class="flex-1 py-1">
                        <h4 class="text-sm font-bold text-slate-900 leading-tight mb-1 line-clamp-1">${item.name}</h4>
                        <p class="text-sm font-extrabold text-slate-900">${item.price_vnd.toLocaleString()}đ</p>
                        <p class="text-[10px] font-semibold text-green-600 mt-1">Giảm tối đa ${(item.max_pts * ptsToVnd).toLocaleString()}đ</p>
                    </div>
                    <button onclick="removeFromCart(${index})" class="text-slate-300 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition-colors self-center">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            `;
        }).join('');

        subtotalVnd.innerText = subtotal.toLocaleString() + "đ";

        // Discount logic
        let discount = 0;
        let ptsUsed = 0;
        
        if (usePointsToggle.checked) {
            ptsUsed = Math.min(userPoints, maxPtsCanUse);
            discount = ptsUsed * ptsToVnd;
            discountRow.classList.remove('hidden');
            discountVnd.innerText = "-" + discount.toLocaleString() + "đ";
            pointInfo.innerHTML = `Đang dùng <b class="text-green-600">${ptsUsed} PTS</b> để giảm <b class="text-green-600">${discount.toLocaleString()}đ</b>`;
        } else {
            discountRow.classList.add('hidden');
            pointInfo.innerHTML = `Bạn có <b class="text-green-600">${userPoints} PTS</b>. Bật để dùng tối đa <b class="text-green-600">${maxPtsCanUse} PTS</b> cho đơn này.`;
        }

        totalVnd.innerText = (subtotal - discount).toLocaleString() + "đ";
    }

    function addToCart(product) {
        cart.push(product);
        localStorage.setItem('green_cart', JSON.stringify(cart));
        if (window.isLoggedIn && window.saveCartToDB) window.saveCartToDB(cart);
        updateCartUI();
        
        const btn = event.currentTarget;
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-white">check</span>';
        btn.classList.add('!bg-slate-900');
        setTimeout(() => {
            btn.innerHTML = originalIcon;
            btn.classList.remove('!bg-slate-900');
        }, 1500);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('green_cart', JSON.stringify(cart));
        if (window.isLoggedIn && window.saveCartToDB) window.saveCartToDB(cart);
        updateCartUI();
    }

    function toggleCart() {
        const sidebar = document.getElementById('cartSidebar');
        const overlay = document.getElementById('cartOverlay');
        sidebar.classList.toggle('translate-x-full');
        overlay.classList.toggle('opacity-0');
        overlay.classList.toggle('pointer-events-none');
    }

    function checkout() {
        if (!window.isLoggedIn) {
            window.showToast('Vui lòng đăng nhập để thực hiện thanh toán!', 'error');
            setTimeout(() => window.location.href = 'auth/login.php', 1500);
            return;
        }
        if (cart.length === 0) {
            window.showToast('Giỏ hàng của bạn đang trống!', 'error');
            return;
        }

        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang xử lý...';
        btn.disabled = true;

        const usePoints = document.getElementById('usePointsToggle').checked;

        fetch('api_checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart: cart, use_points: usePoints })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.showToast(data.message, 'success');
                cart = [];
                localStorage.removeItem('green_cart');
                updateCartUI();
                toggleCart();
                // Tải lại trang sau 2 giây để cập nhật điểm mới nhất
                setTimeout(() => window.location.reload(), 2000);
            } else {
                window.showToast(data.message, 'error');
            }
        })
        .catch(err => {
            window.showToast('Đã có lỗi xảy ra. Vui lòng thử lại!', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    document.getElementById('productSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(term) ? 'flex' : 'none';
        });
    });

    document.getElementById('aiSearchBtn').addEventListener('click', () => {
        document.getElementById('aiImageInput').click();
    });

    document.getElementById('aiImageInput').addEventListener('change', function(e) {
        if (e.target.files.length === 0) return;
        
        const btn = document.getElementById('aiSearchBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang AI...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('image', e.target.files[0]);

        fetch('api_ai_search.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const keyword = data.data.detected_keyword;
                const searchInput = document.getElementById('productSearch');
                searchInput.value = keyword;
                
                // Trigger search event
                searchInput.dispatchEvent(new Event('input'));
                
                alert(`Trợ lý AI đã phát hiện: ${keyword} (Độ chính xác: ${data.data.confidence})`);
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            alert('Lỗi kết nối tới AI Server.');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            e.target.value = ''; // Reset input
        });
    });

    updateCartUI();
</script>

<?php include 'includes/footer.php'; ?>
