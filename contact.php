<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>
<!-- Banner phụ -->
<section class="bg-[#123321] pt-32 pb-24 relative overflow-hidden text-center">
    <div class="absolute inset-0 bg-[#22c55e]/5 mix-blend-overlay"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10" data-aos="fade-up">
        <div class="w-16 h-16 bg-[#22c55e] rounded-2xl flex items-center justify-center text-[#123321] font-black text-3xl mx-auto mb-6 shadow-[0_0_20px_rgba(34,197,94,0.4)]">
            <span class="material-symbols-outlined">mail</span>
        </div>
        <h1 class="text-5xl font-black text-white uppercase tracking-tighter mb-4">Liên Hệ</h1>
        <p class="text-[#22c55e] font-bold text-lg">Kết nối với đội ngũ phát triển.</p>
    </div>
</section>

<section class="max-w-6xl mx-auto px-6 -mt-12 relative z-20 mb-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contact Info Cards -->
        <div class="space-y-6">
            <div class="vibrant-card p-8" data-aos="fade-right" data-aos-delay="100">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-[#22c55e] mb-6">
                    <span class="material-symbols-outlined">alternate_email</span>
                </div>
                <h4 class="text-xs font-black text-[#123321] uppercase tracking-widest mb-2">Email hỗ trợ</h4>
                <p class="text-sm font-bold text-slate-500">support@greencredit.com</p>
                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">Phản hồi trong 24h</p>
            </div>

            <div class="vibrant-card p-8" data-aos="fade-right" data-aos-delay="200">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-[#22c55e] mb-6">
                    <span class="material-symbols-outlined">call</span>
                </div>
                <h4 class="text-xs font-black text-[#123321] uppercase tracking-widest mb-2">Hotline</h4>
                <p class="text-sm font-bold text-slate-500">1900 6789</p>
                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">Thứ 2 - Thứ 6 (8:00 - 17:00)</p>
            </div>

            <div class="vibrant-card p-8" data-aos="fade-right" data-aos-delay="300">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-[#22c55e] mb-6">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <h4 class="text-xs font-black text-[#123321] uppercase tracking-widest mb-2">Trụ sở chính</h4>
                <p class="text-sm font-bold text-slate-500 leading-relaxed">
                    Tòa nhà Eco-Center, <br>
                    Quận 1, TP. Hồ Chí Minh
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="vibrant-card p-8 md:p-12" data-aos="fade-up">
                <h3 class="text-2xl font-black text-[#123321] uppercase tracking-tighter mb-8">Gửi tin nhắn cho chúng tôi</h3>
                <form action="#" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Họ và tên</label>
                            <input type="text" placeholder="Nguyễn Văn A" class="w-full px-6 py-4 bg-slate-50 rounded-2xl border-none focus:ring-2 focus:ring-[#22c55e] transition-all outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Email</label>
                            <input type="email" placeholder="example@gmail.com" class="w-full px-6 py-4 bg-slate-50 rounded-2xl border-none focus:ring-2 focus:ring-[#22c55e] transition-all outline-none font-bold text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Chủ đề</label>
                        <input type="text" placeholder="Tôi muốn tìm hiểu về đối tác" class="w-full px-6 py-4 bg-slate-50 rounded-2xl border-none focus:ring-2 focus:ring-[#22c55e] transition-all outline-none font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 ml-2">Nội dung tin nhắn</label>
                        <textarea rows="5" placeholder="Nhập tin nhắn của bạn tại đây..." class="w-full px-6 py-4 bg-slate-50 rounded-2xl border-none focus:ring-2 focus:ring-[#22c55e] transition-all outline-none font-bold text-sm resize-none"></textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full py-5 text-[10px] shadow-xl shadow-green-500/10 uppercase tracking-[0.2em]">
                        Gửi yêu cầu ngay
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
