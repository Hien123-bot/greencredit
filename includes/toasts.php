<div id="toast-container" class="fixed bottom-10 right-10 z-[100] space-y-4 pointer-events-none"></div>

<style>
    .toast-animate-in {
        animation: toast-slide-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes toast-slide-in {
        from { transform: translateX(100%) scale(0.9); opacity: 0; }
        to { transform: translateX(0) scale(1); opacity: 1; }
    }
</style>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    
    const icon = type === 'success' ? 'verified' : 'error';
    const bg = type === 'success' ? 'bg-primary' : 'bg-red-500';
    
    toast.className = `flex items-center gap-4 px-8 py-5 ${bg} text-white rounded-3xl shadow-2xl premium-shadow pointer-events-auto toast-animate-in min-w-[320px]`;
    toast.innerHTML = `
        <span class="material-symbols-outlined text-2xl">${icon}</span>
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-0.5">${type === 'success' ? 'Thành công' : 'Thông báo'}</p>
            <p class="text-sm font-black font-heading">${message}</p>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'toast-slide-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) reverse forwards';
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// Global exposure
window.showToast = showToast;
</script>
