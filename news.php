<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php'; 

// Fetch news from DB
try {
    // Lấy bài tiêu điểm (Mới nhất và đã duyệt)
    $stmtFeatured = $pdo->query("SELECT n.*, u.full_name, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.is_featured = 1 AND n.status = 'approved' ORDER BY n.created_at DESC LIMIT 1");
    $featuredPost = $stmtFeatured->fetch(PDO::FETCH_ASSOC);

    // Lấy các bài còn lại (đã duyệt)
    $sqlNormal = "SELECT n.*, u.full_name, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.status = 'approved'";
    if ($featuredPost) {
        $sqlNormal .= " AND n.id != " . $featuredPost['id'];
    }
    $sqlNormal .= " ORDER BY n.created_at DESC";
    $stmtNormal = $pdo->query($sqlNormal);
    $newsList = $stmtNormal->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Lỗi cơ sở dữ liệu: " . $e->getMessage());
}

?>

<div class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6" data-aos="fade-up">
            <div>
                <h2 class="text-green-600 font-bold uppercase tracking-widest mb-3 text-sm">📢 Green Magazine</h2>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Tin tức <br>& Sự kiện</h1>
            </div>
            <p class="max-w-md text-slate-500 font-medium text-lg">
                Khám phá những câu chuyện truyền cảm hứng, cập nhật xu hướng sống xanh và các thông báo mới nhất từ cộng đồng Green Credit.
            </p>
            <div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button onclick="document.getElementById('postModal').classList.remove('hidden')" class="bg-green-500 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-green-600 transition-all shadow-md flex items-center gap-2 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[18px]">edit_square</span> Chia sẻ câu chuyện
                    </button>
                <?php else: ?>
                    <a href="auth/login.php" class="bg-slate-900 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all shadow-md flex items-center gap-2 whitespace-nowrap">
                        Đăng nhập để chia sẻ
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($featuredPost): ?>
        <!-- Featured Post -->
        <div class="relative mb-20 group cursor-pointer" data-aos="zoom-in">
            <div class="aspect-video md:aspect-[21/9] overflow-hidden rounded-[2.5rem] shadow-lg relative bg-slate-900">
                <img src="<?= htmlspecialchars($featuredPost['image']) ?>" 
                     class="w-full h-full object-cover opacity-80 group-hover:scale-105 group-hover:opacity-100 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                
                <div class="absolute bottom-0 left-0 p-10 md:p-16 w-full">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="bg-green-500 text-slate-900 text-xs font-bold px-4 py-1.5 rounded-lg uppercase tracking-wide">Tiêu điểm</span>
                        <span class="text-slate-300 text-sm font-semibold"><?= $featuredPost['reading_time'] ?> Phút đọc</span>
                    </div>
                    <h2 class="text-2xl md:text-4xl font-bold text-white mb-4 max-w-3xl leading-tight">
                        <?= htmlspecialchars($featuredPost['title']) ?>
                    </h2>
                    <p class="text-green-400 font-medium mb-8">
                        Đăng bởi: <?= $featuredPost['author_id'] ? htmlspecialchars($featuredPost['full_name'] ?: $featuredPost['username']) : 'Admin' ?>
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="bg-white text-slate-900 px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-green-500 transition-all shadow-md">Đọc ngay</a>
                        <div class="hidden sm:flex items-center gap-2 text-white/70 hover:text-white transition-colors cursor-pointer">
                            <span class="material-symbols-outlined text-[20px]">share</span>
                            <span class="text-sm font-semibold">Chia sẻ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Category Filter -->
        <div class="flex overflow-x-auto gap-3 mb-10 pb-4 scrollbar-hide" data-aos="fade-up">
            <button class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap shadow-sm">Tất cả bài viết</button>
            <button class="bg-white text-slate-600 px-6 py-3 rounded-xl font-semibold text-sm border border-slate-200 hover:text-slate-900 hover:border-slate-300 whitespace-nowrap transition-all shadow-sm">Sự kiện xanh</button>
            <button class="bg-white text-slate-600 px-6 py-3 rounded-xl font-semibold text-sm border border-slate-200 hover:text-slate-900 hover:border-slate-300 whitespace-nowrap transition-all shadow-sm">Cộng đồng</button>
            <button class="bg-white text-slate-600 px-6 py-3 rounded-xl font-semibold text-sm border border-slate-200 hover:text-slate-900 hover:border-slate-300 whitespace-nowrap transition-all shadow-sm">Mẹo sống bền vững</button>
            <button class="bg-white text-slate-600 px-6 py-3 rounded-xl font-semibold text-sm border border-slate-200 hover:text-slate-900 hover:border-slate-300 whitespace-nowrap transition-all shadow-sm">Thông báo</button>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-24">
            <?php foreach ($newsList as $index => $item): ?>
                <div class="bg-white rounded-3xl overflow-hidden group border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-2 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="<?= ($index % 3) * 50 ?>">
                    <div class="h-56 overflow-hidden relative bg-slate-100">
                        <img src="<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-xs font-bold text-slate-800 shadow-sm"><?= htmlspecialchars($item['tag']) ?></span>
                        </div>
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            <time class="text-xs font-semibold text-slate-400"><?= date('d/m/Y', strtotime($item['created_at'])) ?></time>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1 leading-snug group-hover:text-green-600 transition-colors line-clamp-2">
                            <?= htmlspecialchars($item['title']) ?>
                        </h3>
                        <p class="text-[11px] text-green-600 font-bold uppercase tracking-widest mb-3">
                            Bởi <?= $item['author_id'] ? htmlspecialchars($item['full_name'] ?: $item['username']) : 'Admin' ?>
                        </p>
                        <p class="text-slate-500 text-sm font-medium mb-6 line-clamp-3 leading-relaxed flex-1">
                            <?= htmlspecialchars($item['description']) ?>
                        </p>
                        <a href="#" class="text-sm font-bold text-green-600 flex items-center gap-2 group-hover:gap-3 transition-all mt-auto">
                            Đọc tiếp <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($newsList) && empty($featuredPost)): ?>
                <div class="col-span-full text-center py-20 text-slate-500 font-medium text-lg">
                    Hiện chưa có bài viết nào được đăng tải.
                </div>
            <?php endif; ?>
        </div>

        <!-- Newsletter Section -->
        <div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-xl" data-aos="zoom-in">
            <div class="absolute top-0 right-0 w-80 h-80 bg-green-500/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl -ml-32 -mb-32"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <span class="material-symbols-outlined text-green-500 text-5xl mb-4">mark_email_unread</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-4">Nhận bản tin xanh hằng tuần</h2>
                <p class="text-slate-300 font-medium mb-8 text-lg">Đăng ký để nhận những mẹo sống xanh, thông tin dự án mới và các sự kiện bảo vệ môi trường trực tiếp vào hộp thư của bạn.</p>
                
                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Địa chỉ email của bạn..." 
                           class="flex-1 px-6 py-4 bg-white/10 rounded-xl border border-white/20 text-white placeholder:text-white/50 font-medium outline-none focus:border-green-500 focus:bg-white/20 transition-all">
                    <button class="bg-green-500 text-slate-900 px-8 py-4 rounded-xl font-bold text-sm hover:bg-green-400 transition-all shadow-md">Đăng ký ngay</button>
                </form>
                <p class="text-xs text-slate-500 mt-6 font-medium">Bằng cách đăng ký, bạn đồng ý với <a href="#" class="text-slate-400 underline hover:text-white">chính sách bảo mật</a> của chúng tôi.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Đăng bài của người dùng -->
<div id="postModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl relative">
        <div class="p-6 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-slate-900">Chia sẻ Câu chuyện Xanh</h3>
                <button onclick="document.getElementById('postModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-900 transition-colors w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 hover:bg-slate-100">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="userPostForm" onsubmit="submitPost(event)" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tiêu đề bài viết</label>
                    <input type="text" id="postTitle" required placeholder="VD: Hành trình giảm thiểu nhựa 1 lần của tôi" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-green-500 font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hình ảnh (URL)</label>
                    <input type="url" id="postImage" required placeholder="https://..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-green-500 font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mô tả ngắn</label>
                    <textarea id="postDesc" required rows="2" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-green-500 font-medium"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nội dung chia sẻ</label>
                    <textarea id="postContent" required rows="5" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-green-500 font-medium"></textarea>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="document.getElementById('postModal').classList.add('hidden')" class="flex-1 py-3.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Hủy</button>
                    <button type="submit" id="btnSubmitPost" class="flex-1 py-3.5 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition-colors shadow-sm">Đăng bài</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitPost(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmitPost');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]">sync</span> Đang gửi...';
    btn.disabled = true;

    const data = {
        title: document.getElementById('postTitle').value,
        image: document.getElementById('postImage').value,
        description: document.getElementById('postDesc').value,
        content: document.getElementById('postContent').value
    };

    fetch('api_user_post_news.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('Gửi bài viết thành công! Vui lòng chờ Ban quản trị duyệt bài.');
            document.getElementById('postModal').classList.add('hidden');
            document.getElementById('userPostForm').reset();
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        alert('Có lỗi xảy ra.');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>

<?php include 'includes/footer.php'; ?>
