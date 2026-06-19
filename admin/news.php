<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check admin role here if you have auth setup for admin.
// Assuming admin is logged in for the sake of the mockup.

// Lấy danh sách tin tức
try {
    $stmt = $pdo->query("SELECT n.*, u.full_name, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC");
    $newsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Lỗi cơ sở dữ liệu: " . $e->getMessage());
}

$page_title = 'Quản lý Tin tức';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - News | Green Credit</title>
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
            <a href="products.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-xs uppercase tracking-widest">Quà tặng</span>
            </a>
            <a href="orders.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">shopping_cart</span>
                <span class="text-xs uppercase tracking-widest">Đơn hàng</span>
            </a>
            <a href="news.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
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
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Quản lý Tin Tức</h2>
                <p class="text-gray-500 text-sm font-medium mt-2">Đăng và chỉnh sửa bài viết cho trang Green Magazine</p>
            </div>
            <a href="news_form.php" class="px-6 py-3.5 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-gray-800 transition-all shadow-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Thêm bài viết mới
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($newsList as $item): ?>
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300 flex flex-col">
                <div class="aspect-video bg-gray-50 rounded-[1.5rem] overflow-hidden mb-5 relative">
                    <img src="<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php if($item['is_featured']): ?>
                    <div class="absolute top-3 left-3 bg-yellow-400 text-yellow-900 text-[10px] font-bold px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm">
                        Tiêu điểm
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center gap-2 mb-3 justify-between">
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest"><?= htmlspecialchars($item['tag']) ?></span>
                        <span class="text-xs text-gray-400 font-semibold"><?= date('d/m/Y', strtotime($item['created_at'])) ?></span>
                    </div>
                    <?php if(isset($item['status']) && $item['status'] == 'pending'): ?>
                        <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest">Chờ duyệt</span>
                    <?php else: ?>
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest">Đã duyệt</span>
                    <?php endif; ?>
                </div>
                
                <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 leading-snug"><?= htmlspecialchars($item['title']) ?></h4>
                <p class="text-[11px] text-gray-500 font-medium mb-2 flex-1">
                    Tác giả: <strong class="text-gray-700"><?= $item['author_id'] ? htmlspecialchars($item['full_name'] ?: $item['username']) : 'Admin' ?></strong>
                </p>
                
                <div class="flex flex-col gap-2 mt-auto pt-4 border-t border-gray-50">
                    <?php if(isset($item['status']) && $item['status'] == 'pending'): ?>
                    <div class="flex gap-2">
                        <form action="news_process.php" method="POST" class="flex-1 inline" onsubmit="return confirm('Duyệt bài viết này?');">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="w-full py-2 bg-green-500 text-white font-bold rounded-xl text-xs uppercase tracking-widest text-center hover:bg-green-600 transition-colors">Duyệt</button>
                        </form>
                        <form action="news_process.php" method="POST" class="flex-1 inline" onsubmit="return confirm('Từ chối bài viết này?');">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="w-full py-2 bg-red-100 text-red-600 font-bold rounded-xl text-xs uppercase tracking-widest text-center hover:bg-red-200 transition-colors">Từ chối</button>
                        </form>
                    </div>
                    <?php endif; ?>
                    <div class="flex gap-2">
                        <a href="news_form.php?id=<?= $item['id'] ?>" class="flex-1 py-2.5 bg-gray-50 text-gray-900 font-bold rounded-xl text-xs uppercase tracking-widest text-center hover:bg-gray-100 transition-colors flex items-center justify-center">Sửa</a>
                        <form action="news_process.php" method="POST" class="flex-1 inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này không?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <button type="submit" class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($newsList)): ?>
                <div class="col-span-full text-center py-20 text-gray-500 font-medium">
                    Chưa có bài viết nào. Hãy thêm bài viết mới!
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
