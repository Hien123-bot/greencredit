<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Fetch products from DB
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi cơ sở dữ liệu: " . $e->getMessage());
}

$page_title = 'Quản lý Sản phẩm';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Products | Green Credit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap" rel="stylesheet" />
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex min-h-screen text-gray-900">
    
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col p-8 fixed inset-y-0">
        <h1 class="text-xl font-black text-green-600 tracking-tighter mb-12">GC ADMIN</h1>
        <nav class="flex-grow space-y-2">
            <a href="dashboard.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-xs uppercase tracking-widest">Dashboard</span>
            </a>
            <a href="users.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">group</span>
                <span class="text-xs uppercase tracking-widest">Người dùng</span>
            </a>
            <a href="products.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-xs uppercase tracking-widest">Sản phẩm</span>
            </a>
            <a href="orders.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="text-xs uppercase tracking-widest">Đơn hàng</span>
            </a>
            <a href="news.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">article</span>
                <span class="text-xs uppercase tracking-widest">Tin tức</span>
            </a>
        </nav>
        <div class="mt-auto pt-8">
            <a href="../auth/logout.php" class="flex items-center gap-4 p-4 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-xs uppercase tracking-widest">Đăng xuất</span>
            </a>
        </div>
    </aside>

    <main class="flex-grow ml-64 p-12">
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-8 font-semibold text-sm border border-green-100 flex items-center justify-between">
                <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
                <span class="material-symbols-outlined text-sm cursor-pointer" onclick="this.parentElement.style.display='none'">close</span>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Kho Sản phẩm</h2>
                <p class="text-gray-500 text-sm font-medium mt-2">Quản lý danh sách sản phẩm hiển thị trên Eco Marketplace</p>
            </div>
            <a href="product_form.php" class="px-6 py-3.5 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-gray-800 transition-all shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Thêm sản phẩm mới
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach($products as $p): ?>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300 flex flex-col">
                <div class="aspect-square bg-gray-50 rounded-[1.5rem] overflow-hidden mb-5 relative p-4">
                    <!-- Fix path since admin is in a subfolder, we need to go up one level if the image is local -->
                    <?php 
                    $imgSrc = $p['image'];
                    if (!preg_match('/^http/', $imgSrc)) {
                        $imgSrc = '../' . ltrim($imgSrc, '/');
                    }
                    ?>
                    <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 rounded-xl">
                    <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-bold text-gray-800 uppercase tracking-widest shadow-sm">
                        <?= htmlspecialchars($p['category']) ?>
                    </div>
                </div>
                
                <h4 class="font-bold text-gray-900 mb-1 line-clamp-1 text-sm"><?= htmlspecialchars($p['name']) ?></h4>
                <p class="text-xs text-gray-500 font-medium mb-3 line-clamp-2 leading-relaxed flex-1"><?= htmlspecialchars($p['description']) ?></p>

                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl mb-4">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Giá VND</p>
                        <p class="text-sm font-extrabold text-gray-900"><?= number_format($p['price_vnd']) ?> đ</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Điểm PTS</p>
                        <p class="text-sm font-extrabold text-green-600"><?= number_format($p['max_pts']) ?> pts</p>
                    </div>
                </div>

                <div class="flex gap-2 border-t border-gray-100 pt-4">
                    <a href="product_form.php?id=<?= $p['id'] ?>" class="flex-1 py-2.5 bg-gray-50 text-gray-900 font-bold rounded-xl text-xs uppercase tracking-widest text-center hover:bg-gray-100 transition-colors">Sửa</a>
                    <form action="product_process.php" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if(empty($products)): ?>
                <div class="col-span-full text-center py-20 text-gray-500 font-medium">
                    Chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên!
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
