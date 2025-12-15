<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminPostController extends Controller
{
    // === 1. HÀM HỖ TRỢ TẠO SLUG KHÔNG TRÙNG ===
    private function generateUniqueSlug($string, $id = null)
    {
        $slug = Str::slug($string);
        $originalSlug = $slug;
        $count = 1;

        // Kiểm tra trong database, ngoại trừ bài viết hiện tại (nếu đang sửa)
        while (Post::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // === 2. HIỂN THỊ DANH SÁCH ===
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    // === 3. CHẾ ĐỘ SỬA (Mở Modal) ===
    public function edit($id)
    {
        // Vẫn lấy danh sách để hiển thị nền
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        
        // Lấy bài viết cần sửa để truyền vào Modal
        $postEdit = Post::findOrFail($id);
        
        // Trả về view index nhưng có thêm biến $postEdit
        return view('admin.posts.index', compact('posts', 'postEdit'));
    }

    // === 4. LƯU BÀI VIẾT MỚI ===
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'content' => 'required',
        ]);

        $data = $request->all();
        // Kiểm tra user đăng nhập, nếu không có lấy mặc định là 1 (Admin)
        $data['user_id'] = Auth::id() ?? 1; 

        // --- Xử lý Slug (Chống trùng) ---
        $inputSlug = $request->slug ? $request->slug : $request->title;
        $data['slug'] = $this->generateUniqueSlug($inputSlug);

        // --- Xử lý SEO ---
        $data['meta_title'] = $request->meta_title ?? $request->title;
        
        // --- FIX LỖI UTF-8 Ở ĐÂY: Dùng mb_substr thay vì substr ---
        $data['meta_desc'] = $request->meta_description ?? mb_substr(strip_tags($request->content), 0, 160, 'UTF-8');
        
        // Xóa key thừa
        unset($data['meta_description']);

        // --- Xử lý Ảnh ---
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('images/posts', 'public');
            $data['thumbnail'] = $path;
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Thêm bài viết thành công!');
    }

    // === 5. CẬP NHẬT BÀI VIẾT ===
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();

        // --- Xử lý Slug ---
        $inputSlug = $request->slug ? $request->slug : $request->title;
        $data['slug'] = $this->generateUniqueSlug($inputSlug, $id);

        // --- Xử lý SEO ---
        $data['meta_title'] = $request->meta_title ?? $request->title;
        
        // --- FIX LỖI UTF-8 Ở ĐÂY: Dùng mb_substr thay vì substr ---
        $data['meta_desc'] = $request->meta_description ?? mb_substr(strip_tags($request->content), 0, 160, 'UTF-8');
        
        unset($data['meta_description']);

        // --- Xử lý Ảnh ---
        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ
            if ($post->thumbnail) Storage::disk('public')->delete($post->thumbnail);
            
            // Lưu ảnh mới
            $path = $request->file('thumbnail')->store('images/posts', 'public');
            $data['thumbnail'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    // === 6. XÓA BÀI VIẾT ===
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        
        $post->delete();
        
        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết');
    }
}