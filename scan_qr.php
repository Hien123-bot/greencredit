<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

// Bắt buộc đăng nhập để quét mã
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$display_name = ($user['full_name'] ?? '') ?: ($user['username'] ?? 'User');
$points = $user['points'] ?? 0;

$ranks = [
    ['name' => 'Sơ Cấp', 'min' => 0],
    ['name' => 'Đồng', 'min' => 500],
    ['name' => 'Bạc', 'min' => 1500],
    ['name' => 'Vàng', 'min' => 3000],
    ['name' => 'Bạch Kim', 'min' => 6000],
    ['name' => 'Kim Cương', 'min' => 12000]
];
$current_rank = $ranks[0];
foreach ($ranks as $i => $r) {
    if ($points >= $r['min']) { $current_rank = $r; }
}

// Lấy lượt quét gần nhất nếu có (tùy chọn)
$stmt_history = $pdo->prepare("SELECT * FROM history WHERE user_id = ? AND action_type = 'earn' ORDER BY created_at DESC LIMIT 1");
$stmt_history->execute([$user_id]);
$last_scan = $stmt_history->fetch();

require_once 'includes/header.php'; 
?>

<div class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-3 text-sm">📸 Scan & Earn</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Quét mã tích điểm</h1>
            <p class="text-slate-500 font-medium text-lg">Hướng camera về phía mã QR tại các trạm xanh để ghi nhận điểm thưởng.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Camera View (Left/Center) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="relative" data-aos="zoom-in">
                    <div id="cameraContainer" class="bg-slate-900 rounded-3xl aspect-video md:aspect-[4/3] relative overflow-hidden flex items-center justify-center transition-all duration-500 shadow-lg">
                        <!-- Thẻ div cho camera (html5-qrcode) -->
                        <div id="reader" class="absolute inset-0 w-full h-full z-0" style="display: none;"></div>
                        
                        <!-- Scanning lines -->
                        <div id="scanLine" class="absolute inset-0 border-[3px] border-green-500/30 m-8 rounded-2xl animate-pulse pointer-events-none z-10"></div>
                        <div id="laserLine" class="w-full h-0.5 bg-green-500 absolute top-1/2 -translate-y-1/2 left-0 shadow-[0_0_15px_rgba(34,197,94,0.8)] animate-bounce pointer-events-none z-10"></div>
                        
                        <div id="cameraPlaceholder" class="text-center z-10 pointer-events-none">
                            <span class="material-symbols-outlined text-white/30 text-7xl mb-4 font-light">qr_code_scanner</span>
                            <p class="text-sm font-semibold text-white/50 uppercase tracking-widest">Sẵn sàng quét mã...</p>
                        </div>

                        <!-- Success Overlay (Hidden by default) -->
                        <div id="successOverlay" class="absolute inset-0 bg-green-500 z-20 flex flex-col items-center justify-center text-slate-900 opacity-0 pointer-events-none transition-all duration-300">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-5 shadow-lg">
                                <span class="material-symbols-outlined text-4xl font-bold">check_circle</span>
                            </div>
                            <h3 class="text-2xl font-extrabold tracking-tight mb-1">Đang xử lý!</h3>
                            <p class="font-medium text-green-900">Vui lòng chờ...</p>
                        </div>

                        <!-- Scan Corners -->
                        <div class="absolute top-8 left-8 w-10 h-10 border-t-4 border-l-4 border-green-500 rounded-tl-xl pointer-events-none z-10"></div>
                        <div class="absolute top-8 right-8 w-10 h-10 border-t-4 border-r-4 border-green-500 rounded-tr-xl pointer-events-none z-10"></div>
                        <div class="absolute bottom-8 left-8 w-10 h-10 border-b-4 border-l-4 border-green-500 rounded-bl-xl pointer-events-none z-10"></div>
                        <div class="absolute bottom-8 right-8 w-10 h-10 border-b-4 border-r-4 border-green-500 rounded-br-xl pointer-events-none z-10"></div>
                    </div>
                </div>

                <!-- Simulation Button -->
                <div class="flex flex-col sm:flex-row gap-4" data-aos="fade-up">
                    <button id="startScanBtn" onclick="startRealScan()" class="flex-1 bg-green-500 text-slate-900 py-4 rounded-2xl font-bold text-sm hover:bg-green-400 transition-all shadow-md">
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">photo_camera</span>
                            Bắt đầu quét mã
                        </span>
                    </button>
                    <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                    <button onclick="document.getElementById('qr-input-file').click()" class="flex-1 bg-white border border-slate-200 text-slate-700 py-4 rounded-2xl font-semibold text-sm hover:bg-slate-50 transition-all shadow-sm">
                        <span class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">upload</span>
                            Tải ảnh mã QR
                        </span>
                    </button>
                </div>
            </div>

            <!-- User Info & Results (Right) -->
            <div class="space-y-6" data-aos="fade-left">
                <!-- User Profile Card -->
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16"></div>
                    
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-14 h-14 rounded-xl bg-slate-900 text-green-500 flex items-center justify-center font-bold text-xl shadow-md overflow-hidden shrink-0">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($display_name) ?>&background=0f172a&color=22c55e&size=100" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-slate-900 leading-tight mb-1"><?= htmlspecialchars($display_name) ?></h4>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="text-xs font-semibold text-slate-500">Thành viên <?= $current_rank['name'] ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tổng điểm</p>
                            <p class="text-xl font-extrabold text-slate-900"><?= number_format($points) ?> <span class="text-xs font-semibold text-green-600">PTS</span></p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Xếp hạng</p>
                            <p class="text-xl font-extrabold text-slate-900">Top</p>
                        </div>
                    </div>
                </div>

                <!-- Last Scan Stats (Dynamic) -->
                <div id="scanResultCard" class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm opacity-50 transition-all duration-500">
                    <h3 class="text-sm font-bold text-slate-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                        <span class="material-symbols-outlined text-slate-400">history</span>
                        Kết quả quét vừa rồi
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-sm font-semibold text-slate-500">Điểm nhận được</span>
                            <span id="earnedPts" class="text-base font-bold text-green-600">--</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-50">
                            <span class="text-sm font-semibold text-slate-500">Địa điểm</span>
                            <span id="scanLoc" class="text-sm font-bold text-slate-900 text-right max-w-[150px] truncate">--</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm font-semibold text-slate-500">Giảm thiểu CO2</span>
                            <span id="co2Red" class="text-base font-bold text-emerald-600">--</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-green-50 p-4 rounded-xl text-center border border-green-100">
                        <p class="text-xs font-bold text-green-700 uppercase tracking-wide">Bạn đang làm rất tốt!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrCode;

    // Remove opacity-40 from scanResultCard if last_scan exists
    <?php if($last_scan): ?>
        document.getElementById('scanResultCard').classList.remove('opacity-50');
        document.getElementById('scanResultCard').classList.add('shadow-md');
        document.getElementById('earnedPts').innerText = "+<?= $last_scan['points'] ?> PTS";
        document.getElementById('scanLoc').innerText = "<?= htmlspecialchars($last_scan['description'] ?? 'Trạm Xanh') ?>";
        document.getElementById('co2Red').innerText = "<?= number_format($last_scan['points'] * 0.005, 2) ?> kg";
    <?php endif; ?>

    function startRealScan() {
        const btn = document.getElementById('startScanBtn');
        const overlay = document.getElementById('successOverlay');
        const placeholder = document.getElementById('cameraPlaceholder');
        const reader = document.getElementById('reader');
        
        btn.disabled = true;
        btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-symbols-outlined animate-spin text-[20px]">sync</span>Đang mở camera...</span>';

        placeholder.style.display = 'none';
        reader.style.display = 'block';

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        html5QrCode.start(
            { facingMode: "environment" }, 
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                // Success
                html5QrCode.stop().then(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                    // Chuyển hướng tới qr_handler.php để cộng điểm
                    window.location.href = 'qr_handler.php?code=' + encodeURIComponent(decodedText);
                });
            },
            (errorMessage) => {
                // Ignore parse errors (they happen every frame a QR is not found)
            }
        ).then(() => {
            btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[20px]">document_scanner</span>Đang quét mã...</span>';
        }).catch((err) => {
            alert("Lỗi truy cập camera: " + err);
            btn.disabled = false;
            btn.innerHTML = '<span class="flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[20px]">photo_camera</span>Thử lại</span>';
            placeholder.style.display = 'block';
            reader.style.display = 'none';
        });
    }

    // Xử lý khi upload file ảnh mã QR
    document.getElementById('qr-input-file').addEventListener('change', e => {
        if (e.target.files.length == 0) return;
        const imageFile = e.target.files[0];
        
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        html5QrCode.scanFile(imageFile, true)
        .then(decodedText => {
            const overlay = document.getElementById('successOverlay');
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
            window.location.href = 'qr_handler.php?code=' + encodeURIComponent(decodedText);
        })
        .catch(err => {
            alert("Không tìm thấy mã QR trong ảnh này. Vui lòng thử ảnh rõ nét hơn!");
            e.target.value = ''; // Reset input
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
