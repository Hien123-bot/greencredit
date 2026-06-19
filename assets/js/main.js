// 1. Khởi tạo AOS (Animation On Scroll)
AOS.init({ duration: 800, once: true, offset: 50 });

    // 3. LOGIC HIỆU ỨNG RIPPLE KHI CLICK
    document.addEventListener('mousedown', function(e) {
        const target = e.target.closest('.btn-interact, .btn-primary, .vibrant-card, a.inline-block');
        if (!target) return;

        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        target.appendChild(ripple);

        const rect = target.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        ripple.addEventListener('animationend', () => ripple.remove());
    });

    // 4. Hiệu ứng Particles nền
    function createParticles() {
        const container = document.body;
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 8 + 2 + 'px';
            p.style.width = p.style.height = size;
            p.style.left = Math.random() * 100 + '%';
            p.style.background = i % 2 === 0 ? '#22c55e22' : '#ffffff11';
            p.style.animationDuration = Math.random() * 5 + 5 + 's';
            p.style.animationDelay = Math.random() * 5 + 's';
            container.appendChild(p);
        }
    }
    createParticles();
