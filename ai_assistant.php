<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<div class="min-h-screen bg-[#f8fafc] pt-24 pb-20 px-6">
    <div class="max-w-4xl mx-auto flex flex-col h-[80vh]">
        
        <div class="text-center mb-8" data-aos="fade-up">
            <h2 class="text-green-600 font-bold uppercase tracking-widest mb-2 text-sm">🤖 Trợ lý Ảo</h2>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Green Chatbot</h1>
        </div>

        <div class="flex-1 bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="100">
            <!-- Header -->
            <div class="bg-slate-900 px-6 py-4 flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-slate-900 font-bold shadow-md relative">
                        <span class="material-symbols-outlined">smart_toy</span>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-slate-900 rounded-full animate-pulse"></div>
                    </div>
                    <div>
                        <h3 class="text-white font-bold">EcoBot</h3>
                        <p class="text-green-400 text-xs font-medium">Trực tuyến</p>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div id="chatbox" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                <!-- Welcome Message -->
                <div class="flex gap-4 w-full md:w-3/4">
                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-slate-900 shrink-0 mt-1">
                        <span class="material-symbols-outlined text-[18px]">smart_toy</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-slate-100 shadow-sm">
                        <p class="text-slate-700 text-sm leading-relaxed font-medium">Xin chào <?= isset($_SESSION['user_id']) ? 'bạn' : 'khách' ?>! Tôi là EcoBot của Green Credit. Tôi có thể tư vấn cho bạn các cách sống xanh, tiết kiệm năng lượng, hoặc thông tin về tái chế. Bạn muốn hỏi gì nào?</p>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form id="chatForm" class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-200 focus-within:border-green-500 focus-within:ring-1 focus-within:ring-green-500 transition-all">
                    <button type="button" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-green-600 transition-colors">
                        <span class="material-symbols-outlined">add_circle</span>
                    </button>
                    <input type="text" id="userInput" placeholder="Nhập câu hỏi của bạn (VD: Mẹo tiết kiệm điện, cách tái chế nhựa...)" class="flex-1 bg-transparent border-none outline-none text-slate-700 text-sm font-medium" autocomplete="off" required>
                    <button type="submit" class="w-10 h-10 bg-green-500 text-white rounded-xl flex items-center justify-center hover:bg-green-600 transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const chatbox = document.getElementById('chatbox');
const chatForm = document.getElementById('chatForm');
const userInput = document.getElementById('userInput');

// Mock Data for AI Responses
const mockResponses = [
    { keywords: ['nhựa', 'chai', 'tái chế'], response: "Nhựa mất hàng trăm năm để phân hủy. Thay vì dùng nhựa 1 lần, hãy thử sử dụng túi vải hoặc bình nước cá nhân nhé! Ở Green Credit chúng tôi có rất nhiều sản phẩm thân thiện với môi trường." },
    { keywords: ['điện', 'tiết kiệm'], response: "Để tiết kiệm điện, bạn nên tắt các thiết bị khi không sử dụng, tận dụng ánh sáng tự nhiên và sử dụng bóng đèn LED. Bạn có thể nhận được thêm điểm Green Points nếu tham gia thử thách tiết kiệm năng lượng trong tháng này." },
    { keywords: ['điểm', 'points', 'đổi quà'], response: "Bạn có thể nhận điểm (Green Points) bằng cách quét mã QR tại các trạm tái chế hoặc mua các sản phẩm sinh học. Sau đó dùng điểm để đổi các voucher và quà tặng trên Eco Marketplace nhé!" },
    { keywords: ['xin chào', 'hello', 'hi'], response: "Chào bạn! Tôi có thể giúp gì cho bạn hôm nay trên hành trình sống xanh?" },
];
const defaultResponse = "Đây là một câu hỏi rất thú vị về môi trường. Tuy nhiên, dữ liệu của tôi đang được cập nhật thêm. Bạn có thể hỏi tôi về cách 'tái chế', 'tiết kiệm điện' hoặc cách kiếm 'điểm' nhé!";

function appendMessage(sender, text) {
    const isUser = sender === 'user';
    const msgDiv = document.createElement('div');
    msgDiv.className = `flex gap-4 w-full md:w-3/4 ${isUser ? 'ml-auto flex-row-reverse' : ''}`;
    
    let avatarHtml = '';
    if (isUser) {
        avatarHtml = `<div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-white shrink-0 mt-1"><span class="material-symbols-outlined text-[18px]">person</span></div>`;
    } else {
        avatarHtml = `<div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-slate-900 shrink-0 mt-1"><span class="material-symbols-outlined text-[18px]">smart_toy</span></div>`;
    }

    const bubbleHtml = `
        <div class="${isUser ? 'bg-slate-900 text-white rounded-tr-sm' : 'bg-white text-slate-700 rounded-tl-sm border border-slate-100 shadow-sm'} p-4 rounded-2xl">
            <p class="text-sm leading-relaxed font-medium">${text}</p>
        </div>
    `;

    msgDiv.innerHTML = avatarHtml + bubbleHtml;
    chatbox.appendChild(msgDiv);
    chatbox.scrollTop = chatbox.scrollHeight;
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = userInput.value.trim();
    if (!text) return;

    // Hiển thị tin nhắn người dùng
    appendMessage('user', text);
    userInput.value = '';

    // Hiển thị AI "đang gõ..."
    const typingId = 'typing-' + Date.now();
    const typingDiv = document.createElement('div');
    typingDiv.id = typingId;
    typingDiv.className = 'flex gap-4 w-full md:w-3/4';
    typingDiv.innerHTML = `
        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-slate-900 shrink-0 mt-1"><span class="material-symbols-outlined text-[18px]">smart_toy</span></div>
        <div class="bg-white p-4 rounded-2xl rounded-tl-sm border border-slate-100 shadow-sm flex items-center gap-1">
            <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"></span>
            <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
            <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
        </div>
    `;
    chatbox.appendChild(typingDiv);
    chatbox.scrollTop = chatbox.scrollHeight;

    // Giả lập độ trễ AI
    setTimeout(() => {
        document.getElementById(typingId).remove();
        
        let aiText = defaultResponse;
        const lowerText = text.toLowerCase();
        for (const item of mockResponses) {
            if (item.keywords.some(kw => lowerText.includes(kw))) {
                aiText = item.response;
                break;
            }
        }
        
        appendMessage('bot', aiText);
    }, 1500);
});
</script>

<?php include 'includes/footer.php'; ?>
