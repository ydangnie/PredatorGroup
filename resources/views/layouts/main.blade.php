<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predator Group | Luxury Timepieces</title>
    {{-- Import Font sang trọng --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/layout/main.css', 'resources/js/layout/main.js', 'resources/css/layout/chatbot.css', 'resources/js/layout/chatbot.js'])
</head>

{{-- 1. BANNER CAROUSEL (GIỮ NGUYÊN) --}}
<div class="carousel">
    <div class="list">
        @foreach($banners as $banner)
        <div class="item">
            <img src="{{ asset('storage/' . $banner->hinhanh) }}" alt="{{ $banner->title }}">
            <div class="introduce">
                <div class="title">{{ $banner->title }}</div>
                <div class="topic">{{ $banner->thuonghieu }}</div>
                <div class="des">{{ $banner->mota }}</div>
                <button class="seeMore">SEE MORE &#8599</button>
            </div>
        </div>
        @endforeach
    </div>
    <div class="arrows">
        <button id="prev"><</button>
        <button id="next">></button>
        <button id="back">See All &#8599</button>
    </div>
</div>

{{-- ▼▼▼ PHẦN MỚI: TÍCH HỢP CHRONOSLUX STYLE ▼▼▼ --}}
<div class="clx-wrapper">
    
    <section id="brands" class="clx-section clx-bg-medium">
        <div class="clx-container">
            <h2 class="clx-heading">Thương Hiệu Nổi Bật</h2>
            <p class="clx-subheading">Chúng tôi tự hào giới thiệu ba cái tên dẫn đầu về sự sang trọng, phức tạp và di sản.</p>

            <div class="clx-grid-3">
                <div class="clx-card">
                    <img src="https://images.unsplash.com/photo-1619504046991-9241e32e4496?q=80&w=1974&auto=format&fit=crop" alt="Zenith">
                    <div class="clx-card-body">
                        <h3 class="clx-card-title">ZENITH</h3>
                        <p class="clx-card-subtitle">Độ Chính Xác Vượt Thời Gian</p>
                        <p class="clx-card-desc">Được biết đến với bộ máy El Primero huyền thoại, đại diện cho đỉnh cao chronograph tốc độ cao.</p>
                        <a href="#" class="clx-btn-outline">Khám Phá Ngay</a>
                    </div>
                </div>

                <div class="clx-card">
                    <img src="https://images.unsplash.com/photo-1599880367680-5010e13a543a?q=80&w=1974&auto=format&fit=crop" alt="Audemars Piguet">
                    <div class="clx-card-body">
                        <h3 class="clx-card-title">AUDEMARS PIGUET</h3>
                        <p class="clx-card-subtitle">Biểu Tượng Của Sự Khác Biệt</p>
                        <p class="clx-card-desc">Thách thức các quy tắc truyền thống với thiết kế Royal Oak táo bạo, sang trọng và thể thao.</p>
                        <a href="#" class="clx-btn-outline">Khám Phá Ngay</a>
                    </div>
                </div>

                <div class="clx-card">
                    <img src="https://images.unsplash.com/photo-1619504045453-9689a467d63c?q=80&w=1974&auto=format&fit=crop" alt="Jaeger-LeCoultre">
                    <div class="clx-card-body">
                        <h3 class="clx-card-title">JAEGER-LECOULTRE</h3>
                        <p class="clx-card-subtitle">Nghệ Thuật Chế Tác Đồng Hồ</p>
                        <p class="clx-card-desc">"Nhà sản xuất của các nhà sản xuất", với sự phức tạp cơ khí đáng kinh ngạc.</p>
                        <a href="#" class="clx-btn-outline">Khám Phá Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="collection" class="clx-section clx-bg-dark">
        <div class="clx-container">
            <div class="clx-text-center mb-20">
                <p class="clx-gold-text">TUYỂN CHỌN MÙA THU</p>
                <h2 class="clx-heading-lg">THE PERPETUAL LINEUP</h2>
            </div>

            <div class="clx-showcase-row">
                <div class="clx-showcase-img">
                    <img src="https://images.unsplash.com/photo-1551794726-839d58a6822b?q=80&w=1974&auto=format&fit=crop" alt="Watch Model I">
                    <div class="clx-badge">New Arrival</div>
                </div>
                <div class="clx-showcase-info">
                    <p class="clx-gold-title">Dòng Sản Phẩm Zenith Elite</p>
                    <h3 class="clx-info-title">Chronos Elite Tourbillon</h3>
                    <p class="clx-info-desc">
                        Chiếc đồng hồ này kết hợp bộ máy tourbillon siêu phức tạp với mặt số guilloché thủ công tinh xảo. Vỏ titan được đánh bóng bằng phương pháp thủ công mang lại độ bền và sự nhẹ nhàng đáng kinh ngạc.
                    </p>
                    <ul class="clx-list">
                        <li><span>&gt;</span> Bộ máy tự động Calibre 9001</li>
                        <li><span>&gt;</span> Vỏ Titan cấp độ 5</li>
                        <li><span>&gt;</span> Chống nước 50 mét</li>
                    </ul>
                    <a href="#" class="clx-link-arrow">Xem Chi Tiết Kỹ Thuật <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

            <div class="clx-showcase-row reverse">
                <div class="clx-showcase-img">
                    <img src="https://images.unsplash.com/photo-1523783337698-a164899cc9e4?q=80&w=1974&auto=format&fit=crop" alt="Watch Model II">
                    <div class="clx-badge-dark">Best Seller</div>
                </div>
                <div class="clx-showcase-info">
                    <p class="clx-gold-title">Dòng Sản Phẩm Ocean Master</p>
                    <h3 class="clx-info-title">The Deep Explorer 300</h3>
                    <p class="clx-info-desc">
                        Một tuyệt tác dành cho những nhà thám hiểm. Với thiết kế vỏ bằng thép không gỉ tối màu (DLC coating), mặt số màu xám than và độ bền lặn ấn tượng.
                    </p>
                    <ul class="clx-list">
                        <li><span>&gt;</span> Khóa an toàn chống rò rỉ nước</li>
                        <li><span>&gt;</span> Vỏ thép không gỉ phủ DLC đen</li>
                        <li><span>&gt;</span> Dự trữ năng lượng 70 giờ</li>
                    </ul>
                    <a href="#" class="clx-link-arrow">Xem Chi Tiết Kỹ Thuật <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="clx-section clx-bg-medium">
        <div class="clx-container clx-flex-center">
            <div class="clx-half">
                <h2 class="clx-heading">Triết Lý Của Chúng Tôi</h2>
                <p class="clx-quote">
                    "Đồng hồ không chỉ là công cụ đo thời gian; chúng là những tác phẩm điêu khắc cơ khí, truyền tải di sản và kỹ năng của người thợ thủ công."
                </p>
                <p class="clx-desc">
                    Tại Predator Group, chúng tôi tuyển chọn những chiếc đồng hồ thể hiện sự tôn trọng đối với truyền thống chế tác đồng hồ Thụy Sĩ, đồng thời đón nhận các vật liệu và thiết kế hiện đại.
                </p>
                <a href="#" class="clx-link-gold">TÌM HIỂU THÊM VỀ CHÚNG TÔI &rarr;</a>
            </div>
            <div class="clx-half relative">
                <div class="clx-img-overlay"></div>
                <img src="https://images.unsplash.com/photo-1551420006-a3e34630e1a6?q=80&w=1935&auto=format&fit=crop" class="clx-rounded-img" alt="Craftsmanship">
            </div>
        </div>
    </section>

    <section id="contact" class="clx-section clx-bg-dark border-top">
        <div class="clx-container text-center">
            <h3 class="clx-heading-md">Sẵn Sàng Nâng Tầm Bộ Sưu Tập Của Bạn?</h3>
            <p class="clx-subheading">Liên hệ với chuyên gia tư vấn cá nhân của chúng tôi để được hỗ trợ riêng.</p>
            <a href="#" class="clx-btn-white">YÊU CẦU CUỘC HẸN RIÊNG</a>
        </div>
    </section>

</div>
{{-- ▲▲▲ KẾT THÚC PHẦN MỚI ▲▲▲ --}}

{{-- 6. CHATBOT (GIỮ NGUYÊN) --}}
<div class="chatbot-container">
    <button id="toggle-chat" class="chatbot-toggle-btn">
        {{-- ... Icon chat ... --}}
        <i class="fa-solid fa-comment-dots" style="font-size: 24px;"></i>
    </button>
    
    <div id="chat-box" class="chatbot-window">
        <div class="chat-header">
            <span class="chat-title">PREDATOR AI</span>
            <span id="close-chat-mini" class="chat-close-mini">&times;</span>
        </div>
        <div id="messages" class="chat-messages">
            <div class="message bot-message">Chào bạn! Predator có thể giúp gì cho bạn hôm nay?</div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="user-input" class="chat-input" placeholder="Nhập câu hỏi...">
            <button id="send-btn" class="chat-send-btn"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<div id="chatbot-config" data-url="{{ route('chat.ai') }}" style="display: none;"></div>