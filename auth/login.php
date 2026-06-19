<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

$error = "";
$success = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'invalid') $error = "Email hoặc mật khẩu không chính xác.";
    else if ($_GET['error'] == 'empty') $error = "Vui lòng nhập đầy đủ thông tin.";
}
if (isset($_GET['msg']) && $_GET['msg'] == 'registered') {
    $success = "Đăng ký thành công! Vui lòng đăng nhập.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | Green Credit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/main.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; color: #334155; overflow: hidden; }
        .compact-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
        }
        .mesh-gradient {
            background: radial-gradient(at 0% 0%, #f0fdf4 0px, transparent 50%),
                        radial-gradient(at 100% 100%, #f8fafc 0px, transparent 50%);
            background-color: #ffffff;
        }
    </style>
</head>
<body class="mesh-gradient min-h-screen flex items-center justify-center p-4">
    <!-- Background Decor -->
    <div class="fixed top-0 left-0 w-full h-full -z-10 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-green-500/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-[80px]"></div>
    </div>

    <div class="w-full max-w-md" data-aos="fade-up">
        <!-- Minimal Logo -->
        <div class="flex flex-col items-center mb-8">
            <a href="<?= BASE_URL ?>index.php" class="w-12 h-12 bg-green-500 text-white rounded-xl flex items-center justify-center font-bold text-xl shadow-sm hover:scale-105 transition-transform mb-3">GC</a>
            <h2 class="text-xs font-bold text-green-600 uppercase tracking-widest">Green Fintech Platform</h2>
        </div>

        <!-- Compact Card -->
        <div class="compact-card p-8 md:p-10">
            <header class="mb-8 text-center">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Đăng nhập</h1>
            </header>

            <?php if ($success): ?>
                <div class="bg-green-50 text-green-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold flex items-center gap-2 border border-green-100">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold flex items-center gap-2 border border-red-100">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="login_process.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" required placeholder="name@example.com" 
                           class="w-full px-5 py-3.5 rounded-xl font-medium text-slate-900 text-sm bg-slate-50 border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all outline-none">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-1">
                        <label class="text-xs font-semibold text-slate-600">Mật khẩu</label>
                        <a href="forgot_password.php" class="text-xs font-semibold text-green-600 hover:text-green-700 hover:underline">Quên mật khẩu?</a>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••" 
                           class="w-full px-5 py-3.5 rounded-xl font-medium text-slate-900 text-sm bg-slate-50 border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-slate-900 text-white py-3.5 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-md">
                        Tiếp tục hành trình
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center text-sm font-medium text-slate-500">
                Chưa có tài khoản? <a href="register.php" class="text-green-600 font-semibold hover:underline ml-1">Tham gia ngay</a>
            </div>
        </div>

        <!-- Minimal Footer -->
        <div class="mt-8 text-center text-xs font-semibold text-slate-400">
            © 2026 GREEN CREDIT PLATFORM
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init({ duration: 800 });</script>
</body>
</html>
