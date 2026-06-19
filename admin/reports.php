<?php $page_title = 'Báo cáo & Thống kê'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Reports | Green Credit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
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
            <a href="reports.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
                <span class="material-symbols-outlined">analytics</span>
                <span class="text-xs uppercase tracking-widest">Báo cáo</span>
            </a>
        </nav>
    </aside>

    <main class="flex-grow ml-64 p-12">
        <h2 class="text-3xl font-black tracking-tighter mb-12">Báo cáo hệ thống</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-10">Tăng trưởng người dùng</h3>
                <div class="h-64 bg-gray-50 rounded-3xl flex items-end justify-between p-8 gap-2">
                    <?php for($i=1; $i<=7; $i++): ?>
                    <div class="bg-green-600 rounded-lg w-full" style="height: <?php echo rand(20, 100); ?>%"></div>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-10">Lượt quét AI theo loại</h3>
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold">Nhựa</span>
                        <span class="text-sm font-black">45%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-green-600 h-full w-[45%]"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
