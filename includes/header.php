<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Nếu người dùng đã đăng nhập, luôn lấy dữ liệu mới nhất từ Database
$header_user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT username, full_name, points FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $header_user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Credit - Sovereign Infinity</title>
    
    <!-- Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            color: #334155; /* slate-700 */
        }
        .heading-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #15803d, #22c55e);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        h1, h2, h3, h4, h5, h6 {
            letter-spacing: -0.02em;
            color: #0f172a; /* slate-900 */
        }
    </style>

    <script>
        window.isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
        <?php
        $initial_cart_json = "null";
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT c.quantity, p.* FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $db_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $formatted_cart = [];
            foreach ($db_cart as $item) {
                for ($i=0; $i<$item['quantity']; $i++) {
                    $formatted_cart[] = [
                        'id' => (int)$item['id'],
                        'name' => $item['name'],
                        'price_vnd' => (int)$item['price_vnd'],
                        'max_pts' => (int)$item['max_pts'],
                        'image' => $item['image'],
                        'category' => $item['category']
                    ];
                }
            }
            $initial_cart_json = json_encode($formatted_cart);
        }
        ?>
        window.initialDbCart = <?= $initial_cart_json ?>;
        
        window.saveCartToDB = function(cartArray) {
            if (!window.isLoggedIn) return;
            fetch('<?= BASE_URL ?>api_cart.php?action=save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ cart: cartArray })
            });
        };

        // Cập nhật số lượng giỏ hàng trên tất cả các trang
        document.addEventListener('DOMContentLoaded', function() {
            let localCart = [];
            if (window.isLoggedIn && window.initialDbCart !== null) {
                localCart = window.initialDbCart;
                localStorage.setItem('green_cart', JSON.stringify(localCart));
            } else {
                localCart = JSON.parse(localStorage.getItem('green_cart')) || [];
            }
            const badge = document.getElementById('cart-badge');
            if (badge) {
                if (localCart.length > 0) {
                    badge.innerText = localCart.length;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        });
    </script>
</head>
<body>

<header class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 py-4 px-6 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo Section -->
        <a href="<?= BASE_URL ?>index.php" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-green-500 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                GC
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold tracking-tight text-slate-900 group-hover:text-green-600 transition-colors">Green Credit</span>
                <span class="text-[9px] font-semibold text-green-500 uppercase tracking-widest">Fintech Platform</span>
            </div>
        </a>

        <!-- Navigation Menu -->
        <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
            <a href="<?= BASE_URL ?>index.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">home</span> Trang chủ
            </a>
            <a href="<?= BASE_URL ?>rewards.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">shopping_bag</span> Sản phẩm
            </a>
            <a href="<?= BASE_URL ?>scan_qr.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span> Quét QR
            </a>
            <a href="<?= BASE_URL ?>leaderboard.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">emoji_events</span> Xếp hạng
            </a>
            <a href="<?= BASE_URL ?>ai_assistant.php" class="text-sm font-semibold text-green-600 hover:text-green-700 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">smart_toy</span> Trợ lý AI
            </a>
            <a href="<?= BASE_URL ?>news.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">newspaper</span> Tin tức
            </a>
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-4">
            <!-- Cart Icon -->
            <a href="<?= BASE_URL ?>cart.php" class="relative group p-2 hover:bg-slate-50 rounded-xl transition-all">
                <span class="material-symbols-outlined text-slate-600 group-hover:text-green-600">shopping_cart</span>
                <span id="cart-badge" class="absolute -top-1 -right-1 bg-green-500 text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white hidden shadow-sm">0</span>
            </a>

            <?php if ($header_user): ?>
                <div class="flex items-center gap-3 bg-white p-1.5 pr-4 rounded-xl border border-slate-200 shadow-sm group hover:border-slate-300 transition-colors cursor-pointer relative">
                    <!-- Points Badge -->
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-green-50 rounded-lg border border-green-100 mr-1">
                        <span class="material-symbols-outlined text-[16px] text-green-600">payments</span>
                        <span class="text-xs font-bold text-green-700"><?= number_format($header_user['points']) ?> PTS</span>
                    </div>

                    <!-- User Avatar -->
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center font-bold text-xs overflow-hidden shrink-0">
                        <?php 
                            $header_display_name = ($header_user['full_name'] ?? '') ?: ($header_user['username'] ?? 'User');
                        ?>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($header_display_name) ?>&background=0f172a&color=fff&size=100" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- User Meta -->
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-900"><?= htmlspecialchars($header_display_name) ?></span>
                        <a href="<?= BASE_URL ?>dashboard.php" class="text-[9px] font-medium text-slate-500 hover:text-green-600 transition-colors">Bảng điều khiển</a>
                    </div>

                    <!-- Simple Logout -->
                    <a href="<?= BASE_URL ?>auth/logout.php" class="ml-2 w-7 h-7 rounded-md flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Đăng xuất">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login.php" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors mr-2">Đăng nhập</a>
                <a href="<?= BASE_URL ?>auth/register.php" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-all shadow-sm hover:shadow-md">
                    Bắt đầu ngay
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
