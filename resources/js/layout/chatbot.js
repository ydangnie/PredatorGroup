document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-chat');
    const chatBox = document.getElementById('chat-box');
    const closeBtnMini = document.getElementById('close-chat-mini');
    const sendBtn = document.getElementById('send-btn');
    const userInput = document.getElementById('user-input');
    const messages = document.getElementById('messages');

    // Cấu hình
    const configElement = document.getElementById('chatbot-config');
    const chatUrl = configElement ? configElement.dataset.url : '/chat-ai';
    const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

    // Toggle Chat
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            chatBox.classList.toggle('active');
            toggleBtn.classList.toggle('toggled');
            if (chatBox.classList.contains('active')) {
                setTimeout(() => userInput.focus(), 300);
            }
        });
    }
    if (closeBtnMini) {
        closeBtnMini.addEventListener('click', () => {
            chatBox.classList.remove('active');
            toggleBtn.classList.remove('toggled');
        });
    }

    // Gửi tin nhắn
    function sendMessage(e) {
        if (e) e.preventDefault();

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
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json' // Bắt buộc để Laravel trả về JSON khi lỗi
                },
                body: JSON.stringify({ message: text })
            })
            .then(res => {
                if (!res.ok) {
                    // Nếu lỗi (500, 404, 419), ném ra lỗi để catch bắt
                    return res.text().then(text => { throw new Error(text || res.statusText) });
                }
                return res.json();
            })
            .then(data => {
                removeLoading(loadingId);
                if (data.reply) {
                    appendMessage(data.reply, 'bot');
                }
                if (data.products && data.products.length > 0) {
                    renderProductList(data.products);
                }
            })
            .catch(err => {
                removeLoading(loadingId);
                console.error("Chatbot Error:", err);

                // Hiển thị thông báo lỗi thân thiện hơn
                let errorMsg = 'Xin lỗi, hệ thống đang gặp sự cố.';
                if (err.message && err.message.includes('CSRF')) {
                    errorMsg = 'Phiên làm việc hết hạn. Vui lòng tải lại trang.';
                }
                appendMessage(errorMsg, 'bot');
            });
    }

    // Các hàm render giao diện giữ nguyên...
    function renderProductList(products) {
        const container = document.createElement('div');
        container.style.cssText = "display: flex; flex-direction: column; gap: 8px; margin-top: 10px; padding: 0 5px;";

        products.forEach(p => {
            // Fix lỗi format tiền nếu giá trị null
            const price = p.gia ? new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p.gia) : 'Liên hệ';
            const imgUrl = `/storage/${p.hinh_anh}`;

            const card = document.createElement('a');
            card.href = `/chi-tiet-san-pham/${p.id}`;
            card.style.cssText = `
                display: flex; align-items: center; background: #1e1e1e; border: 1px solid #333; 
                border-radius: 6px; padding: 8px; text-decoration: none; transition: 0.2s; color: #fff;
            `;

            card.innerHTML = `
                <div style="width: 50px; height: 50px; flex-shrink: 0; margin-right: 10px; border-radius: 4px; overflow: hidden; background: #000; border: 1px solid #444;">
                    <img src="${imgUrl}" style="width: 100%; height: 100%; object-fit: cover;" alt="SP" onerror="this.src='https://via.placeholder.com/50'">
                </div>
                <div style="flex-grow: 1; overflow: hidden;">
                    <div style="font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #f0f0f0;">
                        ${p.tensp}
                    </div>
                    <div style="font-size: 12px; color: #d4af37; margin-top: 2px;">${price}</div>
                </div>
            `;
            container.appendChild(card);
        });
        messages.appendChild(container);
        scrollToBottom();
    }

    function appendMessage(text, sender) {
        const div = document.createElement('div');
        div.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;

        // Hỗ trợ Markdown cơ bản cho bot (xuống dòng, in đậm)
        if (sender === 'bot') {
            // Chuyển **text** thành in đậm
            let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            formattedText = formattedText.replace(/\n/g, '<br>');
            div.innerHTML = formattedText;
        } else {
            div.innerText = text;
        }

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

    if (sendBtn) sendBtn.addEventListener('click', sendMessage);
    if (userInput) {
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage(e);
        });
    }
});