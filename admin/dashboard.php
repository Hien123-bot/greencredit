<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Fetch stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalPoints = $pdo->query("SELECT SUM(points) FROM users WHERE role = 'user'")->fetchColumn() ?: 0;
$totalNews = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();

// Fetch recent users
$recentUsers = $pdo->query("SELECT username, email, points, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent orders
$recentOrders = $pdo->query("
    SELECT o.id, u.username, o.total_vnd, o.status, o.created_at 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard | Green Credit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400&display=swap" rel="stylesheet" />
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex min-h-screen text-gray-900">
    
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col p-8 fixed inset-y-0">
        <h1 class="text-xl font-black text-green-600 tracking-tighter mb-12">GC ADMIN</h1>
        <nav class="flex-grow space-y-2">
            <a href="dashboard.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
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
        <div class="mb-12">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Tổng quan Hệ thống</h2>
            <p class="text-gray-500 text-sm font-medium mt-2">Báo cáo nhanh các chỉ số quan trọng của nền tảng Green Credit.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px]">group</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Người dùng</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?= number_format($totalUsers) ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px]">stars</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tổng Điểm</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?= number_format($totalPoints) ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px]">shopping_bag</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Đơn hàng</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?= number_format($totalOrders) ?></p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center gap-6">
                <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px]">article</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Bài viết</p>
                    <p class="text-2xl font-extrabold text-gray-900"><?= number_format($totalNews) ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Users -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Thành viên mới</h3>
                    <a href="users.php" class="text-xs font-bold text-green-600 uppercase tracking-widest hover:underline">Xem tất cả</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Username</th>
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Điểm</th>
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Ngày ĐK</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($recentUsers as $u): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 font-semibold text-gray-900 text-sm"><?= htmlspecialchars($u['username']) ?></td>
                                <td class="p-4 text-green-600 font-bold text-sm"><?= number_format($u['points']) ?></td>
                                <td class="p-4 text-gray-500 font-medium text-sm"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentUsers)): ?>
                                <tr><td colspan="3" class="p-6 text-center text-gray-400 text-sm">Chưa có thành viên nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900">Đơn hàng gần đây</h3>
                    <a href="orders.php" class="text-xs font-bold text-green-600 uppercase tracking-widest hover:underline">Xem tất cả</a>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Mã ĐH</th>
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                                <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php foreach($recentOrders as $o): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 font-bold text-gray-900 text-sm">#<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td class="p-4 text-gray-600 font-medium text-sm"><?= htmlspecialchars($o['username']) ?></td>
                                <td class="p-4">
                                    <?php 
                                        $statusClass = 'bg-gray-100 text-gray-600';
                                        $statusText = 'Chờ xử lý';
                                        if($o['status'] == 'completed') { $statusClass = 'bg-green-100 text-green-700'; $statusText = 'Hoàn thành'; }
                                        if($o['status'] == 'cancelled') { $statusClass = 'bg-red-100 text-red-700'; $statusText = 'Đã hủy'; }
                                    ?>
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentOrders)): ?>
                                <tr><td colspan="3" class="p-6 text-center text-gray-400 text-sm">Chưa có đơn hàng nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
