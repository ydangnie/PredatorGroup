<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airpods Animation | @coding.stella</title>
    @vite(['resources/css/layout/main.css', 'resources/js/layout/main.js', 'resources/css/layout/chatbot.css' ])
</head>

<div class="carousel">
    <div class="list">
        {{-- Bắt đầu vòng lặp hiển thị Banner từ Database --}}
        @foreach($banners as $banner)
        <div class="item">
            {{-- Hiển thị ảnh --}}
            <img src="{{ asset('storage/' . $banner->hinhanh) }}" alt="{{ $banner->title }}">

            <div class="introduce">
                <div class="title">{{ $banner->title }}</div>
                <div class="topic">{{ $banner->thuonghieu }}</div>
                <div class="des">
                    {{ $banner->mota }}
                </div>
                {{-- Nút xem thêm có thể gắn link nếu muốn --}}
                <button class="seeMore">SEE MORE &#8599</button>
            </div>

            {{-- Giữ lại phần detail nếu cần, hoặc có thể ẩn đi vì bảng Banner hiện tại chưa có dữ liệu chi tiết --}}
            {{-- <div class="detail"> ... </div> --}}
        </div>
        @endforeach
        {{-- Kết thúc vòng lặp --}}
    </div>

    <div class="arrows">
        <button id="prev">
            << /button>
                <button id="next">></button>
                <button id="back">See All &#8599</button>
    </div>
</div>
<div class="chatbot-container">
    <button id="toggle-chat" class="chatbot-toggle-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H6L4 18V4H20V16Z" fill="currentColor" />
        </svg>
    </button>
    <div class="chatbot-container">
        <button id="toggle-chat" class="chatbot-toggle-btn">
            <svg class="icon-msg" width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H6L4 18V4H20V16Z" fill="currentColor" />
            </svg>
            <svg class="icon-close" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div id="chat-box" class="chatbot-window">
            <div class="chat-header">
                <span class="chat-title">ĐĂNG NIÊ CHATBOT AI</span>
                <span id="close-chat-mini" class="chat-close-mini" title="Đóng">&times;</span>
            </div>

            <div id="messages" class="chat-messages">
                <div class="message bot-message">
                    Chào bạn! Predator có thể hỗ trợ gì cho phong cách của bạn hôm nay?
                </div>
            </div>

            <div class="chat-input-area">
                <input type="text" id="user-input" class="chat-input" placeholder="Nhập câu hỏi...">
                <button id="send-btn" class="chat-send-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-chat');
            const chatBox = document.getElementById('chat-box');
            const closeBtnMini = document.getElementById('close-chat-mini');
            const sendBtn = document.getElementById('send-btn');
            const userInput = document.getElementById('user-input');
            const messages = document.getElementById('messages');

            // --- XỬ LÝ ĐÓNG/MỞ CHAT (Hiệu ứng xoay nút) ---
            function toggleChat() {
                chatBox.classList.toggle('active');
                toggleBtn.classList.toggle('toggled'); // Class này sẽ kích hoạt CSS xoay icon

                // Focus vào ô nhập khi mở chat
                if (chatBox.classList.contains('active')) {
                    setTimeout(() => userInput.focus(), 300);
                }
            }

            toggleBtn.addEventListener('click', toggleChat);

            // Nút đóng nhỏ (X) ở góc trên chatbox
            closeBtnMini.addEventListener('click', () => {
                chatBox.classList.remove('active');
                toggleBtn.classList.remove('toggled');
            });

            // --- XỬ LÝ GỬI TIN NHẮN ---
            function sendMessage() {
                const text = userInput.value.trim();
                if (!text) return;

                // 1. Hiện tin nhắn user
                appendMessage(text, 'user');
                userInput.value = '';

                // 2. Tạo hiệu ứng "Đang nhập..." (...)
                const loadingId = 'loading-' + Date.now();
                const loadingDiv = document.createElement('div');
                loadingDiv.id = loadingId;
                loadingDiv.className = 'message bot-message loading';
                loadingDiv.innerHTML = '<span>.</span><span>.</span><span>.</span>';
                messages.appendChild(loadingDiv);
                scrollToBottom();

                // 3. Gửi API
                fetch('{{ route("chat.ai") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: text
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Xóa loading và hiện câu trả lời
                        removeLoading(loadingId);
                        appendMessage(data.reply, 'ai');
                    })
                    .catch(err => {
                        removeLoading(loadingId);
                        appendMessage('Hệ thống đang bận, vui lòng thử lại sau.', 'ai');
                        console.error(err);
                    });
            }

            // --- HÀM HỖ TRỢ ---
            function appendMessage(text, sender) {
                const div = document.createElement('div');
                div.className = `message ${sender === 'user' ? 'user-message' : 'bot-message'}`;
                div.textContent = text;
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

            // Sự kiện Click và Enter
            sendBtn.addEventListener('click', sendMessage);
            userInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        });
        // Hàm này thay thế hàm xử lý chat cũ của bạn
        function sendMessageToAI() {
            const inputField = document.getElementById('chatInput'); // ID ô nhập liệu
            const message = inputField.value;
            const chatBody = document.getElementById('chatBody'); // ID vùng hiển thị chat

            if (!message) return;

            // 1. Hiển thị tin nhắn người dùng
            chatBody.innerHTML += `<div class="user-message" style="text-align: right; margin: 10px; color: #fff;">${message}</div>`;
            inputField.value = '';

            // 2. Gửi request
            fetch('{{ route("chat.ai") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // 3. Hiển thị tin nhắn của AI
                    chatBody.innerHTML += `<div class="bot-message" style="text-align: left; margin: 10px; color: #d4af37;">${data.reply}</div>`;

                    // 4. KIỂM TRA VÀ HIỂN THỊ SẢN PHẨM (NẾU CÓ)
                    if (data.products && data.products.length > 0) {
                        let productHtml = `<div class="bot-products" style="display: flex; gap: 10px; overflow-x: auto; padding: 10px;">`;

                        data.products.forEach(product => {
                            // Tạo link chi tiết
                            let detailUrl = `/chi-tiet-san-pham/${product.id}`;
                            let imgUrl = `/storage/${product.hinh_anh}`;
                            let price = new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(product.gia);

                            productHtml += `
                    <a href="${detailUrl}" class="chat-product-card" style="min-width: 120px; background: #222; border: 1px solid #444; border-radius: 8px; padding: 8px; text-decoration: none; color: #fff; transition: 0.3s;">
                        <img src="${imgUrl}" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 5px;">
                        <div style="font-size: 11px; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${product.tensp}</div>
                        <div style="font-size: 10px; color: #d4af37;">${price}</div>
                    </a>
                `;
                        });

                        productHtml += `</div>`;
                        chatBody.innerHTML += productHtml;
                    }

                    // Scroll xuống cuối
                    chatBody.scrollTop = chatBody.scrollHeight;
                })
                .catch(error => console.error('Error:', error));
        }
        // Hàm này thay thế hàm xử lý chat cũ của bạn
        function sendMessageToAI() {
            const inputField = document.getElementById('chatInput'); // ID ô nhập liệu
            const message = inputField.value;
            const chatBody = document.getElementById('chatBody'); // ID vùng hiển thị chat

            if (!message) return;

            // 1. Hiển thị tin nhắn người dùng
            chatBody.innerHTML += `<div class="user-message" style="text-align: right; margin: 10px; color: #fff;">${message}</div>`;
            inputField.value = '';

            // 2. Gửi request
            fetch('{{ route("chat.ai") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // 1. Hiển thị lời thoại AI
                    chatBody.innerHTML += `<div class="bot-message">${data.reply}</div>`;

                    // 2. Hiển thị Danh sách sản phẩm (NẾU CÓ)
                    if (data.products && data.products.length > 0) {
                        let productHtml = `<div class="bot-products" style="display:flex; gap:10px; overflow-x:auto; padding:10px; margin-top:5px;">`;

                        data.products.forEach(p => {
                            // Định dạng giá tiền
                            let price = new Intl.NumberFormat('vi-VN').format(p.gia);

                            // Đường dẫn ảnh và link chi tiết
                            let imgUrl = `/storage/${p.hinh_anh}`;
                            let linkUrl = `/chi-tiet-san-pham/${p.id}`; // Link chuyển trang

                            productHtml += `
                <a href="${linkUrl}" class="chat-product-card" style="min-width:120px; text-decoration:none; color:#fff; background:#222; border-radius:8px; padding:8px; border:1px solid #444;">
                    <img src="${imgUrl}" style="width:100%; height:80px; object-fit:cover; border-radius:4px;">
                    <div style="font-size:11px; font-weight:bold; margin-top:5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${p.tensp}</div>
                    <div style="font-size:10px; color:#d4af37;">${price} đ</div>
                </a>
            `;
                        });

                        productHtml += `</div>`;
                        chatBody.innerHTML += productHtml;
                    }
                    // Scroll xuống cuối
                    chatBody.scrollTop = chatBody.scrollHeight;
                })
        }
    </script>