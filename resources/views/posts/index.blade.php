<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức & Sự Kiện | Predator Group</title>
    <meta name="description" content="Cập nhật tin tức mới nhất về đồng hồ cao cấp và phong cách sống thượng lưu.">

    {{-- Fonts & CSS từ dự án (Copy từ layout main) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/layout/main.css', 'resources/js/layout/main.js'])
    
    <style>
        /* CSS Riêng cho trang News để không ảnh hưởng trang khác */
        body { background-color: #0b0b0b; color: #fff; font-family: 'Inter', sans-serif; }
        .page-header { padding: 120px 0 60px; text-align: center; background: #111; }
        .news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; padding: 50px 0; }
        
        /* Card Style Luxury */
        .news-card { background: #1a1a1a; border: 1px solid #333; transition: 0.3s; overflow: hidden; }
        .news-card:hover { border-color: #c5a059; transform: translateY(-5px); }
        .news-img-wrap { height: 240px; overflow: hidden; }
        .news-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .news-card:hover .news-img-wrap img { transform: scale(1.1); }
        
        .news-body { padding: 25px; }
        .news-date { color: #c5a059; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: block; }
        .news-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 15px; line-height: 1.3; }
        .news-title a { color: #fff; text-decoration: none; transition: 0.3s; }
        .news-title a:hover { color: #c5a059; }
        .news-desc { color: #aaa; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        
        .read-more { color: #fff; text-decoration: none; font-size: 0.9rem; border-bottom: 1px solid #c5a059; padding-bottom: 2px; transition: 0.3s; }
        .read-more:hover { color: #c5a059; }

        /* Container chuẩn */
        .container-custom { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .gold-divider { width: 60px; height: 3px; background: #c5a059; margin: 20px auto; }
    </style>
</head>
<body>

    {{-- Include Header --}}
    @include('layouts.navbar.header')

    <main>
        {{-- Banner Tiêu đề --}}
        <section class="page-header">
            <div class="container-custom">
                <span style="color: #c5a059; letter-spacing: 2px; text-transform: uppercase;">The Journal</span>
                <h1 style="font-family: 'Playfair Display', serif; font-size: 3rem; margin-top: 10px;">TIN TỨC & SỰ KIỆN</h1>
                <div class="gold-divider"></div>
            </div>
        </section>

        {{-- Danh sách bài viết --}}
        <section class="container-custom">
            <div class="news-grid">
                @foreach($posts as $post)
                <article class="news-card">
                    <div class="news-img-wrap">
                        <a href="{{ route('posts.show', $post->slug) }}">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}">
                        </a>
                    </div>
                    <div class="news-body">
                        <span class="news-date">{{ $post->created_at->format('d.m.Y') }}</span>
                        <h2 class="news-title">
                            <a href="{{ route('posts.show', $post->slug) }}">{{ Str::limit($post->title, 55) }}</a>
                        </h2>
                        <p class="news-desc">
                            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                        </p>
                        <a href="{{ route('posts.show', $post->slug) }}" class="read-more">ĐỌC TIẾP &rarr;</a>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Phân trang --}}
            <div style="padding-bottom: 80px; display: flex; justify-content: center;">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </section>
    </main>

    {{-- Include Footer --}}
    @include('layouts.navbar.footer')

    {{-- Scripts nếu cần --}}
    @vite(['resources/js/layout/chatbot.js'])
</body>
</html>