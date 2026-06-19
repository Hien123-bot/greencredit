<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

$page_title = $product ? 'Sửa Sản Phẩm' : 'Thêm Sản Phẩm Mới';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?> | Green Credit Admin</title>
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
        <div class="mb-10 flex items-center gap-4">
            <a href="products.php" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900"><?= $page_title ?></h2>
            </div>
        </div>

        <form action="product_process.php" method="POST" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 max-w-4xl">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Tên Sản Phẩm -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?= $product ? htmlspecialchars($product['name']) : '' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900" placeholder="VD: Bình nước tre tự nhiên...">
                </div>

                <!-- Danh mục & Tồn kho -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục</label>
                    <input type="text" name="category" value="<?= $product ? htmlspecialchars($product['category']) : 'Gia dụng' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Số lượng tồn kho</label>
                    <input type="number" name="stock" min="0" value="<?= $product ? $product['stock'] : 100 ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                </div>

                <!-- Giá & Điểm PTS -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price_vnd" required min="0" value="<?= $product ? $product['price_vnd'] : 0 ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Điểm Green Points quy đổi tối đa</label>
                    <input type="number" name="max_pts" min="0" value="<?= $product ? $product['max_pts'] : 0 ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                    <p class="text-xs text-gray-400 mt-2 font-medium">Số điểm lớn nhất người dùng có thể dùng để giảm giá.</p>
                </div>

                <!-- Hình ảnh -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Hình Ảnh</label>
                    <input type="text" name="image" required value="<?= $product ? htmlspecialchars($product['image']) : '' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900" placeholder="https://... hoặc assets/images/...">
                    <p class="text-xs text-gray-400 mt-2 font-medium">Bạn có thể sử dụng link từ Unsplash, Imgur, hoặc đường dẫn ảnh cục bộ.</p>
                </div>

                <!-- Mô tả -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả sản phẩm</label>
                    <textarea name="description" rows="4" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900"><?= $product ? htmlspecialchars((string)$product['description']) : '' ?></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t border-gray-100 pt-8">
                <a href="products.php" class="px-8 py-4 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition-all">Hủy bỏ</a>
                <button type="submit" class="px-10 py-4 bg-green-500 text-gray-900 font-bold rounded-xl text-sm hover:bg-green-400 transition-all shadow-lg shadow-green-500/30">
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </main>
</body>
</html>
