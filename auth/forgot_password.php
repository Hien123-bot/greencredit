<?php
$page_title = 'Quên mật khẩu | Green Credit';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <?php include '../includes/header.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100">
        <div class="text-center mb-10">
            <h1 class="text-2xl font-black text-green-600 mb-2">QUÊN MẬT KHẨU</h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Nhập email để khôi phục</p>
        </div>
        <form action="#" method="POST" class="space-y-6">
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase px-4">Địa chỉ Email</label>
                <input type="email" name="email" class="w-full px-6 py-4 bg-gray-50 border border-transparent rounded-2xl focus:bg-white focus:border-green-600 focus:ring-4 focus:ring-green-600/5 font-bold transition-all outline-none">
            </div>
            <button type="submit" class="w-full py-5 bg-gray-900 text-white font-black rounded-2xl shadow-xl shadow-gray-900/10 hover:bg-green-600 transition-all uppercase tracking-widest text-[11px]">Gửi yêu cầu</button>
            <p class="text-center text-xs text-gray-400 mt-6"><a href="login.php" class="text-green-600 font-bold">Quay lại đăng nhập</a></p>
        </form>
    </div>
</body>
</html>
