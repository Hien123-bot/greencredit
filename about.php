<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="bg-slate-900 pt-32 pb-24 relative overflow-hidden text-center">
    <div class="absolute inset-0 bg-green-500/5 mix-blend-overlay"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-500/10 rounded-full blur-[100px] -mr-32 -mt-32"></div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10" data-aos="fade-up">
        <span class="inline-block bg-white/10 text-green-400 font-bold uppercase tracking-widest text-[10px] px-4 py-2 rounded-full mb-6 border border-white/10">
            Nền tảng Green Fintech vì tương lai bền vững
        </span>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-6 leading-tight">
            Kiến Tạo Hệ Sinh Thái <br>
            <span class="text-green-500">Tiêu Dùng Bền Vững</span>
        </h1>
        <p class="text-slate-300 font-medium text-lg max-w-2xl mx-auto leading-relaxed">
            Green Credit là nền tảng Green Fintech tiên phong kết nối công nghệ số và tiêu dùng bền vững. Chúng tôi chuyển hóa mọi hành động thân thiện với môi trường của bạn thành "Green Points" có giá trị thực tế.
        </p>
    </div>
</section>

<!-- Stats Section (Counter) -->
<section class="max-w-6xl mx-auto px-6 -mt-12 relative z-20 mb-24">
    <div class="bg-white p-8 md:p-12 rounded-[2rem] border border-slate-100 shadow-lg" data-aos="fade-up" data-aos-delay="100">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100">
            <div class="text-center px-4">
                <p class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">10K+</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Người dùng tiên phong</p>
            </div>
            <div class="text-center px-4">
                <p class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">88+</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Cửa hàng đối tác</p>
            </div>
            <div class="text-center px-4">
                <p class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">1.5M+</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Ống hút được thay thế</p>
            </div>
            <div class="text-center px-4">
                <p class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2 text-green-500">100%</p>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Cam kết bền vững</p>
            </div>
        </div>
    </div>
</section>

<!-- Storytelling Section -->
<section class="max-w-6xl mx-auto px-6 mb-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div class="relative" data-aos="fade-right">
            <div class="aspect-[4/5] bg-gradient-to-tr from-green-500/20 to-transparent rounded-[3rem] absolute -inset-4 -rotate-3"></div>
            <div class="relative bg-white p-4 rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden h-full">
                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=800" alt="Green Lifestyle" class="w-full h-full object-cover rounded-[2rem]">
            </div>
        </div>
        <div data-aos="fade-left">
            <h2 class="text-sm font-bold text-green-600 uppercase tracking-widest mb-3">Câu chuyện thương hiệu</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">Bắt Đầu Từ Một Lựa Chọn Nhỏ</h3>
            
            <div class="space-y-5 text-slate-600 font-medium leading-relaxed text-lg">
                <p>
                    Chúng ta đang đối mặt với thực trạng đáng báo động: hàng triệu tấn rác thải nhựa tiếp tục đổ ra đại dương mỗi năm, đe dọa trực tiếp đến hệ sinh thái. Rất nhiều người trong chúng ta khao khát một lối sống xanh, nhưng thường dễ dàng từ bỏ vì thiếu đi sự thuận tiện và một động lực rõ ràng để duy trì thói quen.
                </p>
                <p>
                    Cùng lúc đó, các doanh nghiệp có chung tầm nhìn phát triển bền vững lại chật vật trong việc tìm kiếm và giữ chân đúng tệp khách hàng của mình.
                </p>
                <p>
                    Đó là lý do <strong class="text-slate-900">Green Credit</strong> ra đời. Chúng tôi bắt đầu với một bước đi nhỏ nhưng thiết thực: thay thế hoàn toàn ống hút nhựa bằng ống hút sinh học từ mo dừa. Bằng việc kết hợp sức mạnh của công nghệ, Green Credit tạo ra một hệ sinh thái liền mạch nơi người tiêu dùng, cửa hàng đối tác và nhà cung cấp được kết nối chặt chẽ. Ở đây, mọi lựa chọn bền vững của bạn không còn đơn độc, mà luôn được đo lường, ghi nhận và đền đáp xứng đáng.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="bg-slate-50 py-24 border-y border-slate-100 mb-24">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            <!-- Vision -->
            <div data-aos="fade-up">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-green-600 mb-6 shadow-sm border border-slate-100">
                    <span class="material-symbols-outlined text-[28px]">visibility</span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-6">Tầm nhìn chiến lược</h3>
                <p class="text-xl font-medium leading-relaxed text-slate-600 italic">
                    "Mục tiêu của chúng tôi là trở thành nền tảng Green Fintech số 1 tại Việt Nam. Xây dựng một xã hội nơi mọi cá nhân và doanh nghiệp đều xem phát triển bền vững là nền tảng, nơi mỗi hành động bảo vệ môi trường, dù là nhỏ nhất, đều được ghi nhận bằng giá trị kinh tế thực tế."
                </p>
            </div>

            <!-- Mission -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-green-600 mb-6 shadow-sm border border-slate-100">
                    <span class="material-symbols-outlined text-[28px]">flag</span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-6">Sứ mệnh cốt lõi</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-green-500 mt-0.5">check_circle</span>
                        <div>
                            <strong class="text-slate-900 block mb-0.5">Khuyến khích tiêu dùng xanh</strong>
                            <span class="text-slate-500 font-medium">Biến các sản phẩm sinh học thân thiện với môi trường trở thành tiêu chuẩn mới.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-green-500 mt-0.5">check_circle</span>
                        <div>
                            <strong class="text-slate-900 block mb-0.5">Hỗ trợ doanh nghiệp</strong>
                            <span class="text-slate-500 font-medium">Cung cấp công cụ giúp doanh nghiệp kết nối với khách hàng trung thành.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-green-500 mt-0.5">check_circle</span>
                        <div>
                            <strong class="text-slate-900 block mb-0.5">Giảm thiểu rác thải nhựa</strong>
                            <span class="text-slate-500 font-medium">Bắt đầu hành động ngay từ những vật dụng dùng một lần hàng ngày.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-green-500 mt-0.5">check_circle</span>
                        <div>
                            <strong class="text-slate-900 block mb-0.5">Tiên phong công nghệ</strong>
                            <span class="text-slate-500 font-medium">Ứng dụng số hóa để đo lường, minh bạch hóa mọi tác động tích cực.</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Values Proposition -->
<section class="max-w-6xl mx-auto px-6 mb-24">
    <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-sm font-bold text-green-600 uppercase tracking-widest mb-3">Giá trị khác biệt</h2>
        <h3 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Tại sao chọn Green Credit?</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Value 1 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="0">
            <div class="text-4xl mb-6">🌱</div>
            <h4 class="text-lg font-bold text-slate-900 mb-3">Tiêu Dùng Xanh</h4>
            <p class="text-sm text-slate-500 font-medium leading-relaxed">
                Mang đến các giải pháp thay thế nhựa dùng một lần, an toàn cho sức khỏe và thân thiện tuyệt đối với môi trường tự nhiên.
            </p>
        </div>

        <!-- Value 2 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
            <div class="text-4xl mb-6">🎁</div>
            <h4 class="text-lg font-bold text-slate-900 mb-3">Tích Điểm & Nhận Thưởng</h4>
            <p class="text-sm text-slate-500 font-medium leading-relaxed">
                Tự động quy đổi mọi giao dịch xanh thành điểm thưởng (Green Points) để nhận hàng ngàn ưu đãi và quà tặng độc quyền.
            </p>
        </div>

        <!-- Value 3 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
            <div class="text-4xl mb-6">📱</div>
            <h4 class="text-lg font-bold text-slate-900 mb-3">Công Nghệ Thông Minh</h4>
            <p class="text-sm text-slate-500 font-medium leading-relaxed">
                Trải nghiệm người dùng mượt mà, ghi nhận giao dịch tức thời, minh bạch và dễ dàng theo dõi hành trình xanh cá nhân.
            </p>
        </div>

        <!-- Value 4 -->
        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
            <div class="text-4xl mb-6">🤝</div>
            <h4 class="text-lg font-bold text-slate-900 mb-3">Hệ Sinh Thái Bền Vững</h4>
            <p class="text-sm text-slate-500 font-medium leading-relaxed">
                Sự gắn kết hoàn hảo giữa người dùng, đối tác và nhà cung cấp, cùng nhau tạo ra một nền kinh tế tuần hoàn mang lại lợi ích.
            </p>
        </div>
    </div>
</section>

<!-- Bottom CTA -->
<section class="max-w-6xl mx-auto px-6 mb-24" data-aos="zoom-in">
    <div class="bg-slate-900 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 left-0 w-80 h-80 bg-green-500/20 rounded-full blur-[100px] -ml-32 -mt-32"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-[100px] -mr-32 -mb-32"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white tracking-tight mb-6 leading-tight">
                Cùng Chúng Tôi Tạo Nên Một Tương Lai Xanh
            </h2>
            <p class="text-slate-300 font-medium text-lg mb-10 leading-relaxed">
                Bạn đã sẵn sàng biến những thói quen nhỏ hàng ngày thành tác động lớn cho hành tinh và nhận lại những phần thưởng xứng đáng chưa?
            </p>
            <a href="auth/register.php" class="inline-flex items-center gap-2 bg-green-500 text-slate-900 px-8 py-4 rounded-xl font-bold text-base hover:bg-green-400 hover:-translate-y-1 transition-all shadow-lg">
                Tham Gia Green Credit Ngay
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
