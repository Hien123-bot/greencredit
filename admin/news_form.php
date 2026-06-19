<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$news = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);
}

$page_title = $news ? 'Sửa bài viết' : 'Thêm bài viết mới';
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
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col p-8 fixed inset-y-0">
        <h1 class="text-xl font-black text-green-600 tracking-tighter mb-12">GC ADMIN</h1>
        <nav class="flex-grow space-y-2">
            <a href="dashboard.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-xs uppercase tracking-widest">Dashboard</span>
            </a>
            <a href="news.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
                <span class="material-symbols-outlined">article</span>
                <span class="text-xs uppercase tracking-widest">Tin tức</span>
            </a>
            <!-- Giữ lại các link khác nếu cần -->
            <a href="products.php" class="flex items-center gap-4 p-4 text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-2xl font-bold transition-all">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="text-xs uppercase tracking-widest">Quà tặng</span>
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
            <a href="news.php" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-gray-900 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900"><?= $page_title ?></h2>
            </div>
        </div>

        <form action="news_process.php" method="POST" class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 max-w-4xl">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Tiêu đề -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tiêu đề bài viết <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?= $news ? htmlspecialchars($news['title']) : '' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900" placeholder="Nhập tiêu đề...">
                </div>

                <!-- Tag & Reading Time -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thẻ Tag (Ví dụ: Sự kiện)</label>
                    <input type="text" name="tag" value="<?= $news ? htmlspecialchars($news['tag']) : 'Tin tức' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thời gian đọc (Phút)</label>
                    <input type="number" name="reading_time" min="1" value="<?= $news ? $news['reading_time'] : 5 ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900">
                </div>

                <!-- Hình ảnh -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Hình Ảnh Cover</label>
                    <input type="url" name="image" required value="<?= $news ? htmlspecialchars($news['image']) : '' ?>" 
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900" placeholder="https://...">
                    <p class="text-xs text-gray-400 mt-2 font-medium">Sử dụng link ảnh trực tiếp để tiết kiệm dung lượng lưu trữ.</p>
                </div>

                <!-- Featured -->
                <div class="col-span-full flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <input type="checkbox" name="is_featured" id="is_featured" class="w-5 h-5 text-green-600 rounded focus:ring-green-500" <?= ($news && $news['is_featured']) ? 'checked' : '' ?>>
                    <label for="is_featured" class="text-sm font-bold text-gray-900 cursor-pointer">Đánh dấu là Bài Viết Tiêu Điểm (Sẽ hiển thị lớn trên cùng trang)</label>
                </div>

                <!-- Mô tả ngắn -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả ngắn (Dùng cho Card)</label>
                    <textarea name="description" rows="3" required class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900"><?= $news ? htmlspecialchars($news['description']) : '' ?></textarea>
                </div>

                <!-- Nội dung -->
                <div class="col-span-full">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nội dung chi tiết (Có thể dùng HTML/Markdown đơn giản)</label>
                    <textarea name="content" rows="10" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-xl font-medium focus:outline-none focus:border-green-500 focus:bg-white transition-all text-gray-900 font-mono text-sm"><?= $news ? htmlspecialchars((string)$news['content']) : '' ?></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4 border-t border-gray-100 pt-8">
                <a href="news.php" class="px-8 py-4 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition-all">Hủy bỏ</a>
                <button type="submit" class="px-10 py-4 bg-green-500 text-gray-900 font-bold rounded-xl text-sm hover:bg-green-400 transition-all shadow-lg shadow-green-500/30">
                    Lưu bài viết
                </button>
            </div>
        </form>
    </main>
</body>
</html>
