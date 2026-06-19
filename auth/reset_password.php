<?php
session_start();
$page_title = 'Đặt lại mật khẩu | Green Credit';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-primary min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    <!-- Abstract background elements -->
    <div class="absolute top-0 left-0 w-full h-full opacity-10">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-white rounded-full blur-[150px] -mr-96 -mt-96"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-primary-light rounded-full blur-[150px] -ml-48 -mb-48"></div>
    </div>

    <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl p-10 relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-slate-900 mb-4">Thiết lập lại</h1>
            <p class="text-gray-500 text-sm font-light">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        <form action="#" class="space-y-6">
            <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest px-4">Mật khẩu mới</label>
                <div class="relative">
                    <input type="password" placeholder="••••••••" class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:border-primary focus:ring-0 transition-all font-bold">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">lock</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-black text-gray-400 uppercase tracking-widest px-4">Xác nhận mật khẩu</label>
                <div class="relative">
                    <input type="password" placeholder="••••••••" class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:border-primary focus:ring-0 transition-all font-bold">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">lock_reset</span>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-primary text-white font-black rounded-2xl shadow-xl shadow-primary/20 hover:bg-slate-900 hover:-translate-y-1 transition-all uppercase tracking-widest text-sm">
                Cập nhật mật khẩu
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-gray-50 text-center">
            <p class="text-sm text-gray-500">
                <a href="login.php" class="text-primary font-black hover:underline">Quay lại đăng nhập</a>
            </p>
        </div>
    </div>
</body>
</html>
