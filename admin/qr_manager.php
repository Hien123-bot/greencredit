<?php $page_title = 'Quản lý mã QR'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - QR Manager | Green Credit</title>
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
            <a href="qr_manager.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
                <span class="material-symbols-outlined">qr_code_2</span>
                <span class="text-xs uppercase tracking-widest">Quản lý QR</span>
            </a>
        </nav>
    </aside>

    <main class="flex-grow ml-64 p-12">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-black tracking-tighter">Hệ thống mã QR</h2>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">Tạo và quản lý mã tích điểm</p>
            </div>
            <button class="px-8 py-4 bg-green-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest">+ Tạo lô mã mới</button>
        </div>

        <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 p-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php for($i=1; $i<=3; $i++): ?>
                <div class="p-8 border border-gray-50 rounded-[2rem] bg-gray-50/30 flex flex-col items-center text-center">
                    <div class="w-32 h-32 bg-white p-4 rounded-2xl shadow-sm mb-6">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=GC-TOKEN-<?php echo $i; ?>" class="w-full h-full">
                    </div>
                    <p class="font-black text-gray-900 mb-1">GC-TOKEN-<?php echo $i; ?></p>
                    <p class="text-[10px] font-bold text-green-600 uppercase tracking-widest mb-6"><?php echo $i * 100; ?> Điểm</p>
                    <div class="flex gap-2 w-full">
                        <button class="flex-grow py-3 bg-white text-gray-900 font-bold rounded-xl text-[10px] uppercase tracking-widest border border-gray-100">In mã</button>
                        <button class="px-4 py-3 bg-red-50 text-red-600 rounded-xl"><span class="material-symbols-outlined text-sm">delete</span></button>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </main>
</body>
</html>
