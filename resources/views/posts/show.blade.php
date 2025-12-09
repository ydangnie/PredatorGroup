<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags Động --}}
    <title>{{ $post->meta_title ?? $post->title }} | Predator Group</title>
    <meta name="description" content="{{ $post->meta_desc ?? Str::limit(strip_tags($post->content), 160) }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/layout/main.css', 'resources/js/layout/main.js'])

    <style>
        body { background-color: #0b0b0b; color: #ddd; font-family: 'Inter', sans-serif; }
        .post-container { max-width: 900px; margin: 0 auto; padding: 120px 20px 80px; }
        
        .post-header { text-align: center; margin-bottom: 50px; }
        .post-cat { color: #c5a059; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; }
        .post-main-title { font-family: 'Playfair Display', serif; font-size: 2.8rem; color: #fff; margin: 15px 0 20px; line-height: 1.2; }
        .post-meta { color: #777; font-size: 0.9rem; }
        
        .post-thumb { width: 100%; height: auto; border-radius: 4px; margin-bottom: 50px; border: 1px solid #333; }
        
        /* Style nội dung bài viết (Content từ Editor) */
        .content-body { font-size: 1.15rem; line-height: 1.8; color: #ccc; }
        .content-body h2, .content-body h3 { font-family: 'Playfair Display', serif; color: #c5a059; margin-top: 40px; margin-bottom: 20px; }
        .content-body p { margin-bottom: 25px; }
        .content-body img { max-width: 100%; height: auto; border-radius: 4px; margin: 20px 0; }
        .content-body blockquote { border-left: 3px solid #c5a059; padding-left: 20px; margin: 30px 0; font-style: italic; color: #fff; }

        /* Bài viết liên quan */
        .related-sec { background: #111; padding: 60px 0; border-top: 1px solid #333; }
        .related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .rel-item img { width: 100%; height: 200px; object-fit: cover; margin-bottom: 15px; }
        .rel-title { font-size: 1.1rem; font-family: 'Playfair Display', serif; }
        .rel-title a { color: #fff; text-decoration: none; }
        .rel-title a:hover { color: #c5a059; }

        @media(max-width: 768px) {
            .post-main-title { font-size: 2rem; }
            .related-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    @include('layouts.navbar.header')

    <main>
        <article class="post-container">
            <header class="post-header">
                <span class="post-cat">News & Events</span>
                <h1 class="post-main-title">{{ $post->title }}</h1>
                <div class="post-meta">
                    Đăng ngày {{ $post->created_at->format('d/m/Y') }} &bull; Bởi Predator Group
                </div>
            </header>

            @if($post->thumbnail)
            <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="post-thumb">
            @endif

            <div class="content-body">
                {!! $post->content !!}
            </div>
        </article>

        {{-- Section Bài Viết Liên Quan --}}
        @if($relatedPosts->count() > 0)
        <section class="related-sec">
            <div style="text-align: center; margin-bottom: 40px;">
                <h3 style="color: #fff; font-family: 'Playfair Display', serif; font-size: 1.8rem;">BÀI VIẾT LIÊN QUAN</h3>
            </div>
            <div class="related-grid">
                @foreach($relatedPosts as $rel)
                <div class="rel-item">
                    <a href="{{ route('posts.show', $rel->slug) }}">
                        <img src="{{ asset('storage/' . $rel->thumbnail) }}" alt="{{ $rel->title }}">
                    </a>
                    <h4 class="rel-title">
                        <a href="{{ route('posts.show', $rel->slug) }}">{{ $rel->title }}</a>
                    </h4>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </main>

    @include('layouts.navbar.footer')

</body>
</html>