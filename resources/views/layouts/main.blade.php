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
        <button id="prev"><</button>
        <button id="next">></button>
        <button id="back">See All &#8599</button>
    </div>
</div>
<div class="chatbot-container">
    <button id="toggle-chat" class="chatbot-toggle-btn">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H6L4 18V4H20V16Z" fill="currentColor"/>
        </svg>
    </button>
<div class="chatbot-container">
    <button id="toggle-chat" class="chatbot-toggle-btn">
        <svg class="icon-msg" width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H6L4 18V4H20V16Z" fill="currentColor"/>
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
        if(chatBox.classList.contains('active')) {
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
            body: JSON.stringify({ message: text })
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
        if(el) el.remove();
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
</script>