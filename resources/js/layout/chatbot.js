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

    // [FIX 1] Biến cờ để chặn click nhiều lần
    let isSending = false;

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

        // [FIX 2] Nếu đang gửi thì chặn lại ngay (Tránh lỗi 429)
        if (isSending) return;

        const text = userInput.value.trim();
        if (!text) return;

        // [FIX 3] Khóa nút và ô nhập liệu
        isSending = true;
        userInput.disabled = true;
        if (sendBtn) sendBtn.disabled = true;

        // Hiển thị tin nhắn người dùng
        appendMessage(text, 'user');
        userInput.value = '';

        // Hiển thị loading
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
                    // Nếu lỗi (500, 404, 419, 429...), ném ra lỗi để catch bắt
                    return res.text().then(text => { throw new Error(text || res.statusText) });
                }
                return res.json();
            })
            .then(data => {
                removeLoading(loadingId);

                // Hiển thị câu trả lời của Bot
                if (data.reply) {
                    appendMessage(data.reply, 'bot');
                }
                // Hiển thị danh sách sản phẩm (nếu có)
                if (data.products && data.products.length > 0) {
                    renderProductList(data.products);
                }
            })
            .catch(err => {
                removeLoading(loadingId);
                console.error("Chatbot Error:", err);

                // Hiển thị thông báo lỗi thân thiện hơn
                let errorMsg = 'Hệ thống đang bận, vui lòng thử lại sau giây lát.';

                // Kiểm tra các lỗi đặc thù
                if (err.message && err.message.includes('CSRF')) {
                    errorMsg = 'Phiên làm việc hết hạn. Vui lòng tải lại trang.';
                } else if (err.message && (err.message.includes('429') || err.message.includes('Too Many Requests'))) {
                    errorMsg = 'Bạn hỏi nhanh quá! Vui lòng đợi 30 giây nhé.';
                }

                appendMessage(errorMsg, 'bot');
            })
            .finally(() => {
                // [FIX 4] Mở khóa lại sau khi xong (Dù thành công hay lỗi)
                isSending = false;
                userInput.disabled = false;
                if (sendBtn) sendBtn.disabled = false;
                userInput.focus(); // Trả lại con trỏ chuột vào ô nhập
            });
    }

    // --- Các hàm render giao diện giữ nguyên ---

    function renderProductList(products) {
        const container = document.createElement('div');
        container.style.cssText = "display: flex; flex-direction: column; gap: 8px; margin-top: 10px; padding: 0 5px;";

        products.forEach(p => {
            // Format tiền tệ
            const price = p.gia ? new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p.gia) : 'Liên hệ';
            // Đường dẫn ảnh (sửa lại cho đúng path storage)
            const imgUrl = p.hinh_anh.startsWith('http') ? p.hinh_anh : `/storage/${p.hinh_anh}`;

            const card = document.createElement('a');
            card.href = `/chi-tiet-san-pham/${p.id}`;
            card.style.cssText = `
                display: flex; align-items: center; background: #1e1e1e; border: 1px solid #333; 
                border-radius: 6px; padding: 8px; text-decoration: none; transition: 0.2s; color: #fff;
            `;
            // Hiệu ứng hover nhẹ
            card.onmouseover = () => card.style.borderColor = '#d4af37';
            card.onmouseout = () => card.style.borderColor = '#333';

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

        // Hỗ trợ Markdown cơ bản cho bot
        if (sender === 'bot') {
            let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>'); // In đậm
            formattedText = formattedText.replace(/\n/g, '<br>'); // Xuống dòng
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

    // Sự kiện click và enter
    if (sendBtn) sendBtn.addEventListener('click', sendMessage);
    if (userInput) {
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage(e);
        });
    }
});