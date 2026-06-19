<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

// Auth Guard
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// 1. Xử lý Cập nhật Hồ sơ (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email)) {
        $error_msg = "Vui lòng điền đầy đủ họ tên và email.";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Cập nhật thông tin cơ bản
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $user_id]);

            // Xử lý đổi mật khẩu nếu có nhập mật khẩu mới
            if (!empty($new_password)) {
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                $current_user = $stmt->fetch();

                if (!password_verify($old_password, $current_user['password'])) {
                    throw new Exception("Mật khẩu cũ không chính xác.");
                }
                if ($new_password !== $confirm_password) {
                    throw new Exception("Xác nhận mật khẩu mới không khớp.");
                }
                if (strlen($new_password) < 6) {
                    throw new Exception("Mật khẩu mới phải từ 6 ký tự trở lên.");
                }

                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
            }

            $pdo->commit();
            $success_msg = "Cập nhật thông tin và mật khẩu thành công!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_msg = $e->getMessage();
        }
    }
}

// 2. Lấy dữ liệu mới nhất từ DB
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 3. Logic tính toán hạng và CO2
$user_points = $user['points'];
$co2_saved = number_format($user_points * 0.005, 2);
$ranks = [
    ['name' => 'Sơ Cấp', 'min' => 0],
    ['name' => 'Đồng', 'min' => 500],
    ['name' => 'Bạc', 'min' => 1500],
    ['name' => 'Vàng', 'min' => 3000],
    ['name' => 'Bạch Kim', 'min' => 6000],
    ['name' => 'Kim Cương', 'min' => 12000]
];
$current_rank = $ranks[0]; $next_rank = $ranks[1];
foreach ($ranks as $i => $r) {
    if ($user_points >= $r['min']) { $current_rank = $r; $next_rank = $ranks[$i+1] ?? null; }
}

// 4. Lấy hoạt động và thống kê
$stmt = $pdo->prepare("SELECT * FROM history WHERE user_id = ? ORDER BY created_at DESC LIMIT 6");
$stmt->execute([$user_id]);
$activities = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM history WHERE user_id = ? AND action_type = 'earn'");
$stmt->execute([$user_id]);
$total_scans = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM history WHERE user_id = ? AND action_type = 'spend'");
$stmt->execute([$user_id]);
$total_redeems = $stmt->fetchColumn();

// 5. Kiểm tra điểm danh hôm nay
$stmt = $pdo->prepare("SELECT id FROM history WHERE user_id = ? AND action_type = 'earn' AND description = 'Điểm danh hằng ngày' AND DATE(created_at) = CURDATE()");
$stmt->execute([$user_id]);
$has_checked_in = $stmt->fetch() ? true : false;

$display_name = ($user['full_name'] ?? '') ?: ($user['username'] ?? 'User');

require_once 'includes/header.php';
?>

<main class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Dashboard Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-10 gap-6 bg-white p-8 rounded-3xl shadow-sm border border-slate-100" data-aos="fade-down">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-green-500 to-emerald-300 p-0.5 shadow-sm">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($display_name) ?>&background=0f172a&color=fff&size=120" class="w-full h-full object-cover">
                    </div>
                </div>
                <div>
                    <h2 class="text-green-600 font-bold uppercase tracking-widest mb-1 text-xs">Tài khoản cá nhân</h2>
                    <div class="flex flex-wrap items-center gap-3 mb-1">
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= htmlspecialchars($display_name) ?></h1>
                        <span class="bg-slate-900 text-white text-xs font-bold px-3 py-1 rounded-lg">ID: GC-<?= str_pad($user['id'], 5, "0", STR_PAD_LEFT) ?></span>
                    </div>
                    <p class="text-slate-500 font-medium text-sm flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-green-500">verified</span>
                        Cấp độ <?= $current_rank['name'] ?> • Hoạt động từ <?= date('m/Y', strtotime($user['created_at'])) ?>
                    </p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex p-1.5 bg-slate-50 rounded-xl gap-1 border border-slate-100">
                <button onclick="switchTab('overview')" id="tab-btn-overview" class="tab-btn active px-6 py-2.5 rounded-lg font-bold text-sm text-slate-500 transition-all">
                    Tổng quan
                </button>
                <button onclick="switchTab('profile')" id="tab-btn-profile" class="tab-btn px-6 py-2.5 rounded-lg font-bold text-sm text-slate-500 transition-all">
                    Hồ sơ & Bảo mật
                </button>
            </div>
        </div>

        <!-- Tab: OVERVIEW -->
        <div id="tab-content-overview" class="tab-content block">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stats Cards -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mb-4">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-500 mb-1">Số dư điểm Green</h4>
                    <p class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= number_format($user_points) ?> <span class="text-sm font-semibold text-green-600">PTS</span></p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all" data-aos="fade-up" data-aos-delay="50">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 mb-4">
                        <span class="material-symbols-outlined">military_tech</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-500 mb-1">Hạng hiện tại</h4>
                    <p class="text-2xl font-extrabold text-slate-900 tracking-tight uppercase"><?= $current_rank['name'] ?></p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-4">
                        <span class="material-symbols-outlined">qr_code_2</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-500 mb-1">Tổng lượt quét</h4>
                    <p class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= $total_scans ?> <span class="text-sm font-semibold text-blue-600">LẦN</span></p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all" data-aos="fade-up" data-aos-delay="150">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4">
                        <span class="material-symbols-outlined">eco</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-500 mb-1">CO2 giảm thiểu</h4>
                    <p class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= $co2_saved ?> <span class="text-sm font-semibold text-emerald-600">KG</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 h-full">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                            <span class="material-symbols-outlined text-slate-400">history</span> Lịch sử hoạt động
                        </h3>
                        <div class="space-y-3">
                            <?php if(empty($activities)): ?>
                                <p class="text-center py-10 text-slate-500 font-medium">Chưa có dữ liệu hoạt động</p>
                            <?php else: foreach($activities as $act): ?>
                                <div class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-2xl transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 <?= $act['action_type'] == 'earn' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' ?> rounded-xl flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[20px]"><?= $act['action_type'] == 'earn' ? 'add_circle' : 'shopping_bag' ?></span>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-bold text-slate-900 leading-snug"><?= htmlspecialchars($act['description']) ?></h5>
                                            <p class="text-xs text-slate-500 mt-0.5"><?= date('H:i • d/m/Y', strtotime($act['created_at'])) ?></p>
                                        </div>
                                    </div>
                                    <span class="font-extrabold text-sm <?= $act['action_type'] == 'earn' ? 'text-green-600' : 'text-slate-900' ?>">
                                        <?= ($act['action_type'] == 'earn' ? '+' : '-') . number_format($act['points']) ?> PTS
                                    </span>
                                </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Rank Progress -->
                <?php if($next_rank): $progress = min(100, (($user_points - $current_rank['min']) / ($next_rank['min'] - $current_rank['min'])) * 100); ?>
                <div class="bg-slate-900 p-8 rounded-3xl text-white relative overflow-hidden shadow-lg h-fit mb-6">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/20 rounded-full blur-3xl"></div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold text-green-400 uppercase tracking-widest mb-6">Thử thách tiếp theo</h3>
                        <div class="flex justify-between items-end mb-3">
                            <p class="text-2xl font-extrabold tracking-tight"><?= $next_rank['name'] ?></p>
                            <span class="text-xs font-semibold text-slate-400">Còn <?= number_format($next_rank['min'] - $user_points) ?> PTS</span>
                        </div>
                        <div class="w-full h-2 bg-slate-800 rounded-full mb-4">
                            <div class="h-full bg-green-500 rounded-full shadow-[0_0_10px_rgba(34,197,94,0.4)]" style="width: <?= $progress ?>%"></div>
                        </div>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed">Đạt hạng <?= $next_rank['name'] ?> để nhận ưu đãi đặc quyền và huy hiệu xanh.</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Daily Check-in -->
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-green-50 rounded-full blur-xl group-hover:scale-150 transition-transform duration-500"></div>
                    <span class="material-symbols-outlined text-4xl text-green-500 mb-3 relative z-10">calendar_month</span>
                    <h3 class="font-bold text-slate-900 mb-1 relative z-10">Điểm danh hằng ngày</h3>
                    <p class="text-xs text-slate-500 mb-4 relative z-10">Đăng nhập mỗi ngày để nhận ngay 50 PTS</p>
                    <button id="btnCheckin" onclick="dailyCheckin()" class="w-full py-3 rounded-xl font-bold text-sm transition-all shadow-sm relative z-10 <?= $has_checked_in ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-green-500 text-white hover:bg-green-600' ?>" <?= $has_checked_in ? 'disabled' : '' ?>>
                        <?= $has_checked_in ? '<span class="flex justify-center items-center gap-2"><span class="material-symbols-outlined text-[18px]">done_all</span>Đã điểm danh hôm nay</span>' : 'Điểm danh ngay' ?>
                    </button>
                </div>
                <script>
                function dailyCheckin() {
                    const btn = document.getElementById('btnCheckin');
                    btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang xử lý...';
                    btn.disabled = true;

                    fetch('api_checkin.php')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                            btn.innerHTML = 'Điểm danh ngay';
                            btn.disabled = false;
                        }
                    })
                    .catch(err => {
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                        btn.innerHTML = 'Điểm danh ngay';
                        btn.disabled = false;
                    });
                }
                </script>
            </div>
        </div>

        <!-- Tab: PROFILE & SECURITY -->
        <div id="tab-content-profile" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Profile Sidebar Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 border-b border-slate-100 pb-4">Thông tin cơ bản</h3>
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[20px]">person</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Tên đăng nhập</p>
                                    <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($user['username']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[20px]">mail</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Email tài khoản</p>
                                    <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-500 uppercase">Vai trò</p>
                                    <p class="text-sm font-bold text-green-600"><?= ($user['role'] ?? 'user') == 'admin' ? 'Quản trị viên' : 'Người dùng' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-8 md:p-10 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="text-xl font-bold text-slate-900 mb-6 border-b border-slate-100 pb-4">Cập nhật thông tin</h3>
                        
                        <?php if($success_msg): ?>
                            <div class="p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 mb-6 text-sm font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">check_circle</span> <?= $success_msg ?>
                            </div>
                        <?php endif; ?>

                        <?php if($error_msg): ?>
                            <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 mb-6 text-sm font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">error</span> <?= $error_msg ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-slate-700">Họ và tên đầy đủ</label>
                                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" 
                                           class="w-full bg-slate-50 p-3.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all font-medium text-slate-900 outline-none">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-slate-700">Địa chỉ Email</label>
                                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                                           class="w-full bg-slate-50 p-3.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all font-medium text-slate-900 outline-none">
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-100">
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-700">Mật khẩu hiện tại</label>
                                        <input type="password" name="old_password" 
                                               class="w-full bg-slate-50 p-3.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all font-medium text-slate-900 outline-none"
                                               placeholder="Nhập mật khẩu hiện tại (nếu muốn đổi mật khẩu mới)">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-slate-700">Mật khẩu mới</label>
                                            <input type="password" name="new_password" 
                                                   class="w-full bg-slate-50 p-3.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all font-medium text-slate-900 outline-none"
                                                   placeholder="Ít nhất 6 ký tự">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-semibold text-slate-700">Xác nhận mật khẩu</label>
                                            <input type="password" name="confirm_password" 
                                                   class="w-full bg-slate-50 p-3.5 rounded-xl border border-slate-200 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all font-medium text-slate-900 outline-none"
                                                   placeholder="Nhập lại mật khẩu mới">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button type="submit" name="update_profile" class="bg-slate-900 text-white px-8 py-3.5 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-md">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<style>
    .tab-btn.active { background: white; color: #0f172a; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
    .tab-content.hidden { display: none; }
    .tab-content.block { display: block; }
</style>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(c => {
            c.classList.add('hidden');
            c.classList.remove('block');
        });
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
        });

        document.getElementById('tab-content-' + tab).classList.remove('hidden');
        document.getElementById('tab-content-' + tab).classList.add('block');
        document.getElementById('tab-btn-' + tab).classList.add('active');
    }
</script>
<?php require_once 'includes/footer.php'; ?>
