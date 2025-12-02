document.addEventListener('DOMContentLoaded', function() {
    // --- 1. KHAI BÁO BIẾN & CẤU HÌNH ---
    const toggleBtn = document.getElementById('toggle-chat');
    const chatBox = document.getElementById('chat-box');
    const closeBtnMini = document.getElementById('close-chat-mini');
    const sendBtn = document.getElementById('send-btn');
    const userInput = document.getElementById('user-input');
    const messages = document.getElementById('messages');

    // Lấy cấu hình từ thẻ ẩn trong HTML
    const configElement = document.getElementById('chatbot-config');
    const chatUrl = configElement ? configElement.dataset.url : '/chat-ai';

    // Lấy CSRF Token từ thẻ meta
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    // --- 2. XỬ LÝ ĐÓNG / MỞ ---
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            chatBox.classList.toggle('active');
            toggleBtn.classList.toggle('toggled');
            if (chatBox.classList.contains('active')) setTimeout(() => userInput.focus(), 300);
        });
    }
    if (closeBtnMini) {
        closeBtnMini.addEventListener('click', () => {
            chatBox.classList.remove('active');
            toggleBtn.classList.remove('toggled');
        });
    }

    // --- 3. GỬI TIN NHẮN ---
    function sendMessage() {
        const text = userInput.value.trim();
        if (!text) return;

        appendMessage(text, 'user');
        userInput.value = '';

        const loadingId = 'loading-' + Date.now();
        showLoading(loadingId);

        fetch(chatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: text })
            })
            .then(res => res.json())
            .then(data => {
                removeLoading(loadingId);
                if (data.reply) appendMessage(data.reply, 'bot');
                if (data.products && data.products.length > 0) renderProductList(data.products);
            })
            .catch(err => {
                removeLoading(loadingId);
                appendMessage('Lỗi kết nối server.', 'bot');
                console.error(err);
            });
    }

    // --- 4. HÀM VẼ DANH SÁCH SẢN PHẨM (List View Dọc) ---
    function renderProductList(products) {
        const container = document.createElement('div');
        // CSS Container: Xếp dọc
        container.style.cssText = "display: flex; flex-direction: column; gap: 8px; margin-top: 10px; padding: 0 5px;";

        products.forEach(p => {
            const price = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p.gia);
            const imgUrl = `/storage/${p.hinh_anh}`;
            const linkUrl = `/chi-tiet-san-pham/${p.id}`;

            const card = document.createElement('a');
            card.href = linkUrl;
            // CSS Thẻ: Flex row (Ảnh trái - Chữ phải)
            card.style.cssText = `
                display: flex; 
                align-items: center;
                background: #1e1e1e; 
                border: 1px solid #333; 
                border-radius: 6px; 
                padding: 8px; 
                text-decoration: none; 
                transition: 0.2s;
                color: #fff;
            `;

            card.innerHTML = `
                <div style="width: 50px; height: 50px; flex-shrink: 0; margin-right: 10px; border-radius: 4px; overflow: hidden; background: #000; border: 1px solid #444;">
                    <img src="${imgUrl}" style="width: 100%; height: 100%; object-fit: cover;" alt="${p.tensp}">
                </div>
                <div style="flex-grow: 1; overflow: hidden;">
                    <div style="font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #f0f0f0;">
                        ${p.tensp}
                    </div>
                    <div style="font-size: 12px; color: #d4af37; margin-top: 2px;">
                        ${price}
                    </div>
                </div>
                <div style="color: #666; font-size: 12px;">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            `;

            // Hover effect bằng JS
            card.onmouseover = function() { this.style.background = '#2a2a2a';
                this.style.borderColor = '#d4af37'; };
            card.onmouseout = function() { this.style.background = '#1e1e1e';
                this.style.borderColor = '#333'; };

            container.appendChild(card);
        });

        messages.appendChild(container);
        scrollToBottom();
    }

    // --- 5. CÁC HÀM HỖ TRỢ KHÁC ---
    function appendMessage(text, sender) {
        const div = document.createElement('div');
        div.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;
        div.innerHTML = text.replace(/\n/g, '<br>');
        messages.appendChild(div);
        scrollToBottom();
    }

    function showLoading(id) {
        const div = document.createElement('div');
        div.id = id;
        div.className = 'message bot-message loading';
        div.innerHTML = '<span>.</span><span>.</span><span>.</span>';
        messages.appendChild(div);
        scrollToBottom();
    }

    function removeLoading(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function scrollToBottom() {
        messages.scrollTop = messages.scrollHeight;
    }

    // Bắt sự kiện
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);
    if (userInput) userInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') sendMessage(); });
});