<?php $page_title = 'Nhật ký quét AI'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - AI Logs | Green Credit</title>
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
            <a href="ai_logs.php" class="flex items-center gap-4 p-4 bg-green-600 text-white rounded-2xl font-bold shadow-lg shadow-green-600/20">
                <span class="material-symbols-outlined">list_alt</span>
                <span class="text-xs uppercase tracking-widest">AI Logs</span>
            </a>
        </nav>
    </aside>

    <main class="flex-grow ml-64 p-12">
        <h2 class="text-3xl font-black tracking-tighter mb-12">Lịch sử nhận diện AI</h2>
        <div class="bg-white rounded-[3rem] shadow-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-10 py-8">Thời gian</th>
                        <th class="px-10 py-8">Người dùng</th>
                        <th class="px-10 py-8">Phân loại</th>
                        <th class="px-10 py-8">Điểm</th>
                        <th class="px-10 py-8">Độ tin cậy</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-bold">
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-all">
                        <td class="px-10 py-6 text-gray-400">23/04/2026 10:30</td>
                        <td class="px-10 py-6">Ngọc Hiển</td>
                        <td class="px-10 py-6 text-blue-600">Nhựa (Bottle)</td>
                        <td class="px-10 py-6 text-green-600">+25 pts</td>
                        <td class="px-10 py-6">98%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
