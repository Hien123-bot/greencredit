<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Fetch orders with user details
$sql = "SELECT o.*, u.username, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Quản lý Đơn hàng';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Orders | Green Credit</title>
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
            <a href="orders.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
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
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-900">Đơn Hàng</h2>
                <p class="text-gray-500 text-sm font-medium mt-2">Quản lý và cập nhật trạng thái các đơn đổi quà của người dùng.</p>
            </div>
            <div class="px-6 py-3 bg-white border border-gray-200 text-gray-900 font-bold rounded-xl text-sm shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                <span>Lọc đơn hàng</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Mã ĐH</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Khách hàng</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Giá trị đơn</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Trạng thái</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest">Ngày đặt</th>
                            <th class="p-6 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach($orders as $o): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-gray-900 text-sm">#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td class="p-6">
                                <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($o['username']) ?></p>
                                <p class="text-xs text-gray-500 font-medium"><?= htmlspecialchars($o['email']) ?></p>
                            </td>
                            <td class="p-6">
                                <p class="font-extrabold text-gray-900 text-sm"><?= number_format($o['total_vnd']) ?> đ</p>
                                <p class="text-xs text-green-600 font-bold">Dùng <?= number_format($o['points_used']) ?> pts</p>
                            </td>
                            <td class="p-6">
                                <?php 
                                    $statusClass = 'bg-yellow-100 text-yellow-700';
                                    $statusText = 'Chờ xử lý';
                                    if($o['status'] == 'completed') { $statusClass = 'bg-green-100 text-green-700'; $statusText = 'Hoàn thành'; }
                                    if($o['status'] == 'cancelled') { $statusClass = 'bg-red-100 text-red-700'; $statusText = 'Đã hủy'; }
                                ?>
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="p-6 text-gray-500 font-medium text-sm"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td class="p-6 text-right">
                                <?php if($o['status'] == 'pending'): ?>
                                <form action="orders_process.php" method="POST" class="inline-block">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <button type="submit" name="status" value="completed" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition-colors" title="Đánh dấu hoàn thành">
                                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                    </button>
                                    <button type="submit" name="status" value="cancelled" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors" title="Hủy đơn" onsubmit="return confirm('Xác nhận hủy đơn hàng này?');">
                                        <span class="material-symbols-outlined text-[20px]">cancel</span>
                                    </button>
                                </form>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 font-medium italic">Đã xử lý</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($orders)): ?>
                            <tr><td colspan="6" class="p-10 text-center text-gray-500 font-medium">Chưa có đơn hàng nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
