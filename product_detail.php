<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("<div style='text-align:center; padding: 50px; font-family: sans-serif'><h2>Sản phẩm không tồn tại.</h2><a href='rewards.php'>Quay lại cửa hàng</a></div>");
}

// Fetch related products
$stmtRel = $pdo->prepare("SELECT * FROM products WHERE id != ? ORDER BY id DESC LIMIT 4");
$stmtRel->execute([$id]);
$related = $stmtRel->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-6xl mx-auto">
        <a href="rewards.php" class="inline-flex items-center gap-2 text-slate-500 font-semibold text-sm mb-10 hover:text-green-600 transition-colors group">
            <span class="material-symbols-outlined text-[20px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Quay lại cửa hàng
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            <!-- Product Image Section -->
            <div class="relative" data-aos="fade-right">
                <div class="aspect-square rounded-3xl overflow-hidden shadow-sm border border-slate-100 bg-white p-6 md:p-10">
                    <img src="<?= $p['image'] ?>" alt="<?= $p['name'] ?>" class="w-full h-full object-cover rounded-2xl hover:scale-105 transition-transform duration-700">
                </div>
                <!-- Points Badge -->
                <div class="absolute top-6 right-6 bg-green-500 text-slate-900 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-wide shadow-md">
                    Sản phẩm xanh
                </div>
            </div>

            <!-- Content Section -->
            <div class="space-y-8" data-aos="fade-left">
                <div>
                    <span class="text-green-600 font-bold uppercase tracking-widest mb-3 block text-sm"><?= $p['category'] ?></span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4 leading-tight"><?= $p['name'] ?></h1>
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= number_format($p['price_vnd']) ?> <span class="text-lg font-semibold text-slate-400">VNĐ</span></span>
                        <div class="h-6 w-[1px] bg-slate-200 hidden sm:block"></div>
                        <div class="flex items-center gap-1.5 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                            <span class="material-symbols-outlined text-[20px]">redeem</span>
                            <span class="text-xs font-bold">Dùng tới <?= $p['max_pts'] ?> PTS</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-3 border-b border-slate-100 pb-3">Mô tả sản phẩm</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm md:text-base">
                        <?= $p['desc'] ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 shrink-0">
                            <span class="material-symbols-outlined text-[24px]">eco</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-0.5">Tác động</h4>
                            <p class="text-sm font-bold text-slate-900">-0.5kg CO2 phát thải</p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 shrink-0">
                            <span class="material-symbols-outlined text-[24px]">verified</span>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-0.5">Chứng nhận</h4>
                            <p class="text-sm font-bold text-slate-900">Standard 100</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <div class="flex items-center bg-white rounded-xl border border-slate-200 p-1 shadow-sm w-full sm:w-32 justify-between">
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all"><span class="material-symbols-outlined text-[20px]">remove</span></button>
                        <input type="number" value="1" class="w-10 text-center font-bold text-slate-900 text-sm bg-transparent border-none outline-none appearance-none">
                        <button class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-all"><span class="material-symbols-outlined text-[20px]">add</span></button>
                    </div>
                    <button onclick='addToCart(<?= json_encode($p) ?>)' class="flex-1 bg-green-500 text-slate-900 py-4 rounded-xl font-bold text-sm shadow-md hover:bg-green-400 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                        Thêm vào giỏ hàng
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Section -->
        <div class="mt-24 pt-16 border-t border-slate-200">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-8">Sản phẩm liên quan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <?php 
                foreach($related as $rp): 
                ?>
                <a href="product_detail.php?id=<?= $rp['id'] ?>" class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm group hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="aspect-square rounded-xl overflow-hidden mb-4 bg-slate-50 p-2">
                        <img src="<?= $rp['image'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 rounded-lg">
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 mb-1 line-clamp-1 group-hover:text-green-600 transition-colors"><?= $rp['name'] ?></h4>
                    <p class="text-sm font-extrabold text-green-600"><?= number_format($rp['price_vnd']) ?> đ</p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
// Tận dụng hàm addToCart từ logic chung
function addToCart(product) {
    let cart = [];
    if (window.isLoggedIn && window.initialDbCart !== null) {
        cart = window.initialDbCart;
    } else {
        cart = JSON.parse(localStorage.getItem('green_cart')) || [];
    }
    
    // Quick quantity grab
    let qty = parseInt(document.querySelector('input[type="number"]').value) || 1;
    
    // Add multiple times or structure item to have qty
    // For simplicity, just pushing the same object since the logic in cart.php handles array length and maps
    for(let i=0; i<qty; i++) {
        cart.push(product);
    }
    
    localStorage.setItem('green_cart', JSON.stringify(cart));
    if (window.isLoggedIn && window.saveCartToDB) window.saveCartToDB(cart);
    
    // Update badge visually if header is present
    const headerBadge = document.getElementById('cart-badge');
    if(headerBadge) {
        headerBadge.innerText = cart.length;
        headerBadge.classList.remove('hidden');
    }
    
    // Visual feedback
    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined text-[20px]">check</span> Đã thêm';
    btn.classList.remove('bg-green-500');
    btn.classList.add('bg-slate-900', 'text-white');
    
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.classList.add('bg-green-500');
        btn.classList.remove('bg-slate-900', 'text-white');
    }, 2000);
}
</script>

<?php require_once 'includes/footer.php'; ?>
