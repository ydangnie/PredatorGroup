<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predator Group | Luxury Timepieces</title>
    {{-- Import Font sang trọng --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <button id="prev">
            < </button>
                <button id="next">></button>
                <button id="back">See All &#8599</button>
    </div>
</div>

{{-- ▼▼▼ PHẦN MỚI: TÍCH HỢP CHRONOSLUX STYLE ▼▼▼ --}}
<div class="clx-wrapper">

    <section id="brands" class="clx-section clx-bg-medium">
    <div class="clx-container">
        <div class="clx-text-center mb-20">
            <span class="clx-gold-text">Đối Tác Chính Hãng</span>
            <h2 class="clx-heading">THƯƠNG HIỆU DANH TIẾNG</h2>
            <div class="gold-line-center"></div>
        </div>

        <div class="clx-grid-3">
            {{-- Nếu có biến $brands từ Controller thì dùng vòng lặp --}}
            @if(isset($brands) && count($brands) > 0)
                @foreach($brands as $brand)
                <div class="clx-card brand-card">
                    <div class="clx-card-img-wrapper">
                        {{-- Hiển thị Logo hoặc Ảnh đại diện --}}
                        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->ten_thuonghieu }}">
                    </div>
                    <div class="clx-card-body">
                        <h3 class="clx-card-title">{{ $brand->ten_thuonghieu }}</h3>
                        {{-- Mô tả ngắn (có thể thêm cột 'mota' vào bảng brands nếu cần) --}}
                        <p class="clx-card-desc">Biểu tượng của sự đẳng cấp và di sản vượt thời gian.</p>
                        <a href="#" class="clx-btn-link">Xem Bộ Sưu Tập <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            
              
            @endif
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
                    <img src="{{ asset('storage/images/img1.webp') }}" alt="Watch Model I">
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
                    <img src="{{ asset('storage/images/img2.webp') }}" alt="Watch Model II">
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
                <img src="{{ asset('storage/images/img3.webp') }}" class="clx-rounded-img" alt="Craftsmanship">
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