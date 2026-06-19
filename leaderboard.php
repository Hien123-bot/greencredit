<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch top 20 users
$stmt = $pdo->query("SELECT username, full_name, points FROM users WHERE role = 'user' ORDER BY points DESC LIMIT 20");
$top_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getRankName($points) {
    if ($points >= 12000) return 'Kim Cương';
    if ($points >= 6000) return 'Bạch Kim';
    if ($points >= 3000) return 'Vàng';
    if ($points >= 1500) return 'Bạc';
    if ($points >= 500) return 'Đồng';
    return 'Sơ Cấp';
}

function getRankColor($points) {
    if ($points >= 12000) return 'text-cyan-500 bg-cyan-50 border-cyan-100'; // Kim Cương
    if ($points >= 6000) return 'text-slate-500 bg-slate-100 border-slate-200'; // Bạch Kim
    if ($points >= 3000) return 'text-yellow-500 bg-yellow-50 border-yellow-100'; // Vàng
    if ($points >= 1500) return 'text-gray-400 bg-gray-50 border-gray-100'; // Bạc
    if ($points >= 500) return 'text-amber-600 bg-amber-50 border-amber-100'; // Đồng
    return 'text-green-600 bg-green-50 border-green-100'; // Sơ Cấp
}
?>

<div class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-3 text-sm">🏆 Wall of Fame</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Bảng xếp hạng</h1>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">Vinh danh những cá nhân có đóng góp tích cực nhất cho phong trào sống xanh thông qua nền tảng Green Credit.</p>
        </div>

        <?php if (empty($top_users)): ?>
            <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">emoji_events</span>
                <p class="text-slate-500 font-semibold text-lg">Chưa có dữ liệu xếp hạng</p>
            </div>
        <?php else: ?>
            <!-- Top 3 Podium -->
            <div class="flex flex-col md:flex-row items-end justify-center gap-6 mb-12" data-aos="zoom-in">
                <!-- Top 2 -->
                <?php if (isset($top_users[1])): ?>
                <div class="order-2 md:order-1 flex flex-col items-center w-full md:w-1/3">
                    <div class="relative mb-4">
                        <div class="w-20 h-20 rounded-full border-4 border-slate-300 bg-white overflow-hidden shadow-lg z-10 relative">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($top_users[1]['full_name'] ?: $top_users[1]['username']) ?>&background=cbd5e1&color=0f172a" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-3 -right-2 w-8 h-8 bg-slate-300 rounded-full flex items-center justify-center text-slate-800 font-bold border-2 border-white shadow-sm z-20">2</div>
                    </div>
                    <div class="bg-white w-full rounded-t-3xl pt-8 pb-6 px-4 text-center border-t border-x border-slate-100 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)] relative z-0 -mt-10">
                        <h3 class="font-bold text-slate-900 line-clamp-1"><?= htmlspecialchars($top_users[1]['full_name'] ?: $top_users[1]['username']) ?></h3>
                        <p class="text-sm font-extrabold text-green-600 mt-1"><?= number_format($top_users[1]['points']) ?> PTS</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Top 1 -->
                <?php if (isset($top_users[0])): ?>
                <div class="order-1 md:order-2 flex flex-col items-center w-full md:w-1/3 z-10">
                    <div class="relative mb-4">
                        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-yellow-500 text-3xl animate-bounce">👑</div>
                        <div class="w-28 h-28 rounded-full border-4 border-yellow-400 bg-white overflow-hidden shadow-xl z-10 relative">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($top_users[0]['full_name'] ?: $top_users[0]['username']) ?>&background=facc15&color=0f172a" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-3 -right-2 w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center text-slate-900 font-black border-2 border-white shadow-md z-20 text-lg">1</div>
                    </div>
                    <div class="bg-gradient-to-b from-yellow-50 to-white w-full rounded-t-3xl pt-10 pb-8 px-4 text-center border-t border-x border-yellow-100 shadow-[0_-10px_40px_-15px_rgba(250,204,21,0.2)] relative z-0 -mt-12">
                        <h3 class="font-extrabold text-slate-900 text-lg line-clamp-1"><?= htmlspecialchars($top_users[0]['full_name'] ?: $top_users[0]['username']) ?></h3>
                        <p class="text-base font-black text-yellow-600 mt-1"><?= number_format($top_users[0]['points']) ?> PTS</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Top 3 -->
                <?php if (isset($top_users[2])): ?>
                <div class="order-3 flex flex-col items-center w-full md:w-1/3">
                    <div class="relative mb-4">
                        <div class="w-20 h-20 rounded-full border-4 border-amber-600 bg-white overflow-hidden shadow-lg z-10 relative">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($top_users[2]['full_name'] ?: $top_users[2]['username']) ?>&background=d97706&color=fff" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-3 -right-2 w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-sm z-20">3</div>
                    </div>
                    <div class="bg-white w-full rounded-t-3xl pt-8 pb-6 px-4 text-center border-t border-x border-slate-100 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)] relative z-0 -mt-10">
                        <h3 class="font-bold text-slate-900 line-clamp-1"><?= htmlspecialchars($top_users[2]['full_name'] ?: $top_users[2]['username']) ?></h3>
                        <p class="text-sm font-extrabold text-green-600 mt-1"><?= number_format($top_users[2]['points']) ?> PTS</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- List of other ranks -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden" data-aos="fade-up">
                <?php foreach ($top_users as $index => $user): ?>
                    <?php if ($index < 3) continue; ?>
                    <div class="flex items-center justify-between p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-5">
                            <span class="w-8 text-center font-bold text-slate-400">#<?= $index + 1 ?></span>
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-slate-100">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name'] ?: $user['username']) ?>&background=f1f5f9&color=64748b" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 leading-tight"><?= htmlspecialchars($user['full_name'] ?: $user['username']) ?></h4>
                                <?php $rankColor = getRankColor($user['points']); ?>
                                <span class="inline-block px-2 py-0.5 mt-1 rounded text-[10px] font-bold border <?= $rankColor ?> uppercase tracking-wider">
                                    <?= getRankName($user['points']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-slate-900"><?= number_format($user['points']) ?> <span class="text-xs text-green-600">PTS</span></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
