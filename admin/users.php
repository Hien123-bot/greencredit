<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Fetch users
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Quản lý Người dùng';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Users | Green Credit</title>
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
            <a href="users.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
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
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Người Dùng</h2>
                <p class="text-gray-500 text-sm font-medium mt-2">Xem và quản lý danh sách thành viên trên hệ thống</p>
            </div>
            <div class="px-6 py-3 bg-white border border-gray-200 text-gray-900 font-bold rounded-xl text-sm shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">search</span>
                <input type="text" placeholder="Tìm kiếm user..." class="border-none outline-none focus:ring-0 text-sm w-48">
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">ID</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Tài khoản</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Họ Tên</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Tổng Điểm</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Ngày ĐK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($users as $u): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="p-6 text-gray-400 font-semibold text-sm">#<?= $u['id'] ?></td>
                            <td class="p-6 font-bold text-gray-900 text-sm flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-xs">
                                    <?= strtoupper(substr($u['username'], 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($u['username']) ?>
                            </td>
                            <td class="p-6 text-gray-600 font-medium text-sm"><?= htmlspecialchars($u['full_name'] ?? '-') ?></td>
                            <td class="p-6 text-gray-600 font-medium text-sm"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="p-6 text-green-600 font-extrabold text-sm text-right"><?= number_format($u['points']) ?> PTS</td>
                            <td class="p-6 text-gray-400 font-medium text-sm"><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($users)): ?>
                            <tr><td colspan="6" class="p-10 text-center text-gray-500 font-medium">Chưa có người dùng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
