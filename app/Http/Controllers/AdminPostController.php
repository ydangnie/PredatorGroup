<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    // Danh sách tin tức
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    // Form thêm mới
    public function create()
    {
        return view('admin.posts.create');
    }

    // Lưu tin tức mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Xử lý upload ảnh
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images/posts', $fileName, 'public');
            $data['thumbnail'] = $path;
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết thành công!');
    }

    // Form sửa
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // Cập nhật tin tức
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $request->validate([
            'title' => 'required|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'content' => 'required'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $file = $request->file('thumbnail');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('images/posts', $fileName, 'public');
            $data['thumbnail'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    // Xóa tin tức
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết!');
    }
}