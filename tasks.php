<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

// Fetch all tasks
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
$tasks = $stmt->fetchAll();

// Fetch user completed tasks if logged in
$completed_tasks = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT task_id FROM user_tasks WHERE user_id = ? AND status = 'completed'");
    $stmt->execute([$_SESSION['user_id']]);
    $completed_tasks = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

require_once 'includes/header.php'; 
?>

<div class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-3 text-sm">✅ Missions</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Nhiệm vụ hàng ngày</h1>
            <p class="text-slate-500 font-medium text-lg">Hoàn thành các thử thách sống xanh để nhận điểm thưởng cực lớn.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (empty($tasks)): ?>
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 font-light">task</span>
                    <p class="text-slate-500 font-semibold">Chưa có nhiệm vụ nào</p>
                </div>
            <?php else: ?>
                <?php foreach ($tasks as $index => $task): 
                    $is_completed = in_array($task['id'], $completed_tasks);
                ?>
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm group relative overflow-hidden transition-all hover:shadow-md" data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>">
                    <?php if ($is_completed): ?>
                        <div class="absolute inset-0 bg-white/70 backdrop-blur-[2px] z-10 flex items-center justify-center pointer-events-none">
                            <div class="bg-green-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span> Đã hoàn thành
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="flex justify-between items-start mb-6 relative z-0">
                        <div class="w-14 h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center border border-green-100">
                            <span class="material-symbols-outlined text-3xl font-light"><?= htmlspecialchars($task['icon'] ?: 'eco') ?></span>
                        </div>
                        <span class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-lg border border-green-200">+<?= $task['points'] ?> PTS</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug relative z-0"><?= htmlspecialchars($task['title']) ?></h3>
                    <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed relative z-0"><?= htmlspecialchars($task['description']) ?></p>
                    
                    <div class="flex items-center justify-between pt-5 border-t border-slate-100 relative z-0">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">schedule</span>
                            <span class="text-xs font-semibold text-slate-500">Hạn: <?= $task['expire_hours'] ?>h</span>
                        </div>
                        <button onclick="completeTask(<?= $task['id'] ?>, this)" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-all shadow-sm <?= $is_completed ? 'opacity-50 pointer-events-none' : '' ?>">Thực hiện</button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function completeTask(taskId, btn) {
    if (!window.isLoggedIn) {
        alert('Vui lòng đăng nhập để làm nhiệm vụ!');
        setTimeout(() => window.location.href = 'auth/login.php', 500);
        return;
    }

    const proof = prompt("Vui lòng nhập minh chứng (ví dụ: link hình ảnh hoặc mô tả ngắn gọn):");
    if (!proof || proof.trim() === '') {
        alert("Bạn phải cung cấp minh chứng để hoàn thành!");
        return;
    }

    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px] align-middle">sync</span>';
    btn.disabled = true;

    fetch('api_complete_task.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ task_id: taskId, proof: proof })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Tạo overlay hoàn thành
            const card = btn.closest('.bg-white');
            const overlay = document.createElement('div');
            overlay.className = 'absolute inset-0 bg-white/70 backdrop-blur-[2px] z-10 flex items-center justify-center pointer-events-none transition-all';
            overlay.innerHTML = '<div class="bg-green-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-md"><span class="material-symbols-outlined text-[18px]">check_circle</span> Đã hoàn thành</div>';
            card.appendChild(overlay);
            btn.classList.add('opacity-50', 'pointer-events-none');
            
            // Xóa dấu hiệu loading
            btn.innerHTML = originalText;
            
            setTimeout(() => window.location.reload(), 1500);
        } else {
            alert(data.message);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Lỗi kết nối. Vui lòng thử lại!');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>

<?php include 'includes/footer.php'; ?>
