document.addEventListener('DOMContentLoaded', function() {
    const aiUpload = document.getElementById('ai-upload');
    const resultsArea = document.getElementById('results-area');
    const aiResults = document.getElementById('ai-results');

    if (aiUpload) {
        aiUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Hiển thị loading (giả lập)
                resultsArea.classList.remove('hidden');
                aiResults.innerHTML = '<div class="col-span-full py-10 text-center text-gray-400 font-bold uppercase tracking-widest animate-pulse">Đang phân tích ảnh...</div>';

                const formData = new FormData();
                formData.append('scan_image', file);

                fetch('api/ai_api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        aiResults.innerHTML = `
                            <div class="bg-green-50 p-6 rounded-2xl border border-green-100 flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Loại rác</p>
                                    <h5 class="text-xl font-black text-gray-900">${data.label}</h5>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Điểm nhận</p>
                                    <h5 class="text-xl font-black text-green-600">+${data.points} pts</h5>
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    aiResults.innerHTML = '<div class="col-span-full py-10 text-center text-red-400 font-bold">Có lỗi xảy ra, vui lòng thử lại.</div>';
                });
            }
        });
    }
});
