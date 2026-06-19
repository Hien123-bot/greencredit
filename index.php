<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Dynamic Background -->
<div class="fixed inset-0 -z-10 bg-[#f8fafc] overflow-hidden">
    <div class="absolute top-[-10%] right-[-5%] w-[800px] h-[800px] bg-[#22c55e]/10 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-[600px] h-[600px] bg-[#3b82f6]/5 rounded-full blur-[100px]"></div>
</div>

<div class="relative z-10">
    <!-- 1. HERO SECTION -->
    <section class="min-h-screen flex items-center px-6 pt-24 pb-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-green-700 bg-green-100/50 border border-green-200 mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    Nền tảng Green Fintech tiên phong
                </div>
                
                <h1 class="text-5xl lg:text-[64px] font-extrabold leading-[1.1] mb-6">
                    Biến hành động xanh <br> 
                    thành <span class="heading-gradient">giá trị thực tế</span>
                </h1>
                
                <p class="text-lg text-slate-500 max-w-lg mb-10 leading-[1.7]">
                    Khám phá hệ sinh thái tiêu dùng bền vững. Thay thế ống hút nhựa bằng sản phẩm sinh học, tích điểm thưởng và tận hưởng những đặc quyền riêng biệt.
                </p>
                
                <div class="flex flex-wrap gap-4 items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>dashboard.php" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold text-base hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">Vào Dashboard</a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>auth/register.php" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-semibold text-base hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10 hover:-translate-y-0.5">Bắt đầu miễn phí</a>
                        <a href="#how-it-works" class="px-6 py-4 text-slate-700 font-semibold text-base hover:text-slate-900 transition-all flex items-center gap-2">Tìm hiểu thêm <span class="material-symbols-outlined text-xl">arrow_forward</span></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="relative w-full aspect-square md:aspect-[4/3] lg:aspect-square" data-aos="zoom-out" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1605600659873-d808a1d8508a?q=80&w=1974&auto=format&fit=crop" class="w-full h-full object-cover rounded-3xl shadow-2xl" alt="Sản phẩm thân thiện môi trường">
                
                <!-- Floating Info Card -->
                <div class="absolute -bottom-6 -left-6 md:bottom-10 md:-left-10 glass-card p-5 rounded-2xl shadow-xl max-w-xs animate-bounce-slow">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-xl">redeem</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 mb-1">Tích điểm tức thì</p>
                            <p class="text-sm text-slate-500 leading-relaxed">Nhận Green Points sau mỗi lần sử dụng sản phẩm sinh học.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. ABOUT US -->
    <section class="py-24 px-6 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">Giải quyết triệt để rác thải nhựa dùng một lần</h2>
                <p class="text-lg text-slate-500 leading-[1.7] mb-6">
                    Mỗi ngày, hàng triệu ống hút nhựa kết thúc vòng đời tại các bãi rác và đại dương. Chúng tôi mang đến một giải pháp thay thế hoàn hảo: <strong>ống hút sinh học từ mo dừa</strong>.
                </p>
                <p class="text-lg text-slate-500 leading-[1.7] mb-8">
                    Green Credit không chỉ cung cấp sản phẩm, chúng tôi xây dựng một nền tảng Fintech kết nối hành vi tiêu dùng xanh với các phần thưởng xứng đáng.
                </p>
                <a href="<?= BASE_URL ?>about.php" class="text-green-600 font-semibold flex items-center gap-2 hover:gap-3 transition-all">
                    Xem báo cáo tác động <span class="material-symbols-outlined text-xl">arrow_forward</span>
                </a>
            </div>
            
            <div class="grid grid-cols-2 gap-4" data-aos="fade-up" data-aos-delay="100">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=2013&auto=format&fit=crop" class="rounded-2xl w-full h-64 object-cover mt-8">
                <img src="https://images.unsplash.com/photo-1588825128773-ce8b725c88b0?q=80&w=2071&auto=format&fit=crop" class="rounded-2xl w-full h-64 object-cover">
            </div>
        </div>
    </section>

    <!-- 3. HOW IT WORKS -->
    <section id="how-it-works" class="py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-[40px] font-bold mb-6 leading-tight">Mô hình tích điểm đơn giản</h2>
                <p class="text-lg text-slate-500 leading-[1.7]">Trải nghiệm liền mạch từ lúc mua sắm đến khi đổi quà, được thiết kế tối ưu cho cuộc sống hiện đại.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl">qr_code_scanner</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">1. Quét mã QR</h3>
                    <p class="text-base text-slate-500 leading-[1.6]">Sử dụng đồ uống tại các cửa hàng đối tác và quét mã QR đính kèm trên bao bì sản phẩm sinh học.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl">toll</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">2. Nhận điểm tự động</h3>
                    <p class="text-base text-slate-500 leading-[1.6]">Hệ thống xác thực giao dịch và cộng ngay Green Points vào ví tài khoản của bạn trong 2 giây.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-2xl">shopping_cart_checkout</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">3. Đổi phần thưởng</h3>
                    <p class="text-base text-slate-500 leading-[1.6]">Sử dụng điểm tích lũy để mua sắm các sản phẩm thân thiện với môi trường hoặc nhận voucher giảm giá.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. BENEFITS -->
    <section class="py-24 px-6 bg-slate-50">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            <div class="lg:col-span-5 sticky top-24" data-aos="fade-right">
                <h2 class="text-3xl md:text-[40px] font-bold mb-6 leading-tight">Lợi ích đa chiều cho mọi thành viên</h2>
                <p class="text-lg text-slate-500 leading-[1.7] mb-8">
                    Green Credit thiết kế một hệ sinh thái Win-Win-Win, nơi lợi ích kinh tế luôn đi đôi với trách nhiệm bảo vệ môi trường.
                </p>
                <a href="<?= BASE_URL ?>auth/register.php" class="inline-block bg-white border border-slate-200 text-slate-900 px-6 py-3 rounded-xl font-semibold text-base hover:bg-slate-50 transition-all">
                    Khám phá ngay
                </a>
            </div>

            <div class="lg:col-span-7 space-y-6">
                <!-- Benefit Card 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex gap-6 items-start" data-aos="fade-up">
                    <div class="w-12 h-12 bg-green-100 text-green-700 rounded-full flex items-center justify-center shrink-0 mt-1">
                        <span class="material-symbols-outlined text-2xl">person</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3">Đối với người dùng</h3>
                        <p class="text-slate-500 leading-[1.6] mb-4">Nhận phần thưởng thực tế từ các hành vi tiêu dùng có ý thức. Giảm chi phí mua sắm các sản phẩm xanh thông qua hệ thống tích điểm.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                <span class="material-symbols-outlined text-green-500 text-lg">check</span> Tích lũy tài sản số (Points)
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                <span class="material-symbols-outlined text-green-500 text-lg">check</span> Đổi quà dễ dàng
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Benefit Card 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex gap-6 items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center shrink-0 mt-1">
                        <span class="material-symbols-outlined text-2xl">storefront</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-3">Đối với doanh nghiệp</h3>
                        <p class="text-slate-500 leading-[1.6] mb-4">Gia tăng lòng trung thành của khách hàng Gen Z. Dễ dàng đạt được các chứng nhận ESG để thu hút đầu tư bền vững.</p>
                        <ul class="space-y-2">
                            <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                <span class="material-symbols-outlined text-green-500 text-lg">check</span> Tăng tỷ lệ giữ chân khách
                            </li>
                            <li class="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                <span class="material-symbols-outlined text-green-500 text-lg">check</span> Nâng cao uy tín thương hiệu
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. STATISTICS -->
    <section class="py-24 px-6 border-y border-slate-100 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-8 gap-y-12">
                <div class="text-center" data-aos="fade-up" data-aos-delay="0">
                    <p class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">10K<span class="text-green-500">+</span></p>
                    <p class="text-sm font-semibold text-slate-500">Người dùng đang hoạt động</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">150<span class="text-green-500">+</span></p>
                    <p class="text-sm font-semibold text-slate-500">Đối tác cửa hàng F&B</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">2M<span class="text-green-500">+</span></p>
                    <p class="text-sm font-semibold text-slate-500">Ống hút nhựa bị loại bỏ</p>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-3">5<span class="text-green-500"> Tấn</span></p>
                    <p class="text-sm font-semibold text-slate-500">Rác thải được giảm thiểu</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. FINAL CTA -->
    <section class="py-32 px-6 bg-slate-900 text-white text-center">
        <div class="max-w-3xl mx-auto" data-aos="zoom-in">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                Bắt đầu hành trình <br />xanh của bạn
            </h2>
            <p class="text-xl text-slate-400 mb-10 leading-[1.7]">
                Tạo tài khoản miễn phí ngay hôm nay để tích lũy giá trị thực tế từ những thay đổi nhỏ nhất.
            </p>
            
            <div class="flex flex-wrap justify-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>dashboard.php" class="bg-green-500 text-slate-900 px-8 py-4 rounded-xl font-semibold text-base hover:bg-green-400 transition-all shadow-lg shadow-green-500/20">
                        Đến trang tổng quan
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>auth/register.php" class="bg-green-500 text-slate-900 px-8 py-4 rounded-xl font-semibold text-base hover:bg-green-400 transition-all shadow-lg shadow-green-500/20">
                        Đăng ký tài khoản
                    </a>
                    <a href="mailto:partner@greencredit.vn" class="bg-slate-800 text-white px-8 py-4 rounded-xl font-semibold text-base hover:bg-slate-700 border border-slate-700 transition-all">
                        Dành cho doanh nghiệp
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
