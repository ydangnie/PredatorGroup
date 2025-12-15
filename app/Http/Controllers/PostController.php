<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Hiển thị danh sách tin tức cho người dùng xem
    public function index()
    {
        // Lấy bài viết mới nhất, phân trang 6 bài/trang
        $posts = Post::orderBy('created_at', 'desc')->paginate(6);
        return view('posts.index', compact('posts'));
    }

    // Hiển thị chi tiết bài viết
    public function show($slug)
    {
        // Tìm bài viết theo slug
        $post = Post::where('slug', $slug)->firstOrFail();
        
        // Gợi ý bài viết liên quan
        $relatedPosts = Post::where('id', '!=', $post->id)
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}