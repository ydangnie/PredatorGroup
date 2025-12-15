<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    // === QUAN TRỌNG: PHẢI CÓ CÁC TRƯỜNG SEO Ở ĐÂY ===
protected $fillable = [
    'title',
    'slug',
    'thumbnail',
    'content',
    'excerpt',          // <--- SỬA: Đổi 'short_description' thành 'excerpt' cho khớp Database
    'meta_title',
    'meta_desc',        // <--- ĐÚNG: Khớp với Database
    'meta_keywords',    // <--- ĐÚNG: Nếu bạn đã chạy lệnh thêm cột này
    'is_active',     // <--- CẨN THẬN: Kiểm tra xem DB có cột này chưa, nếu chưa thì comment lại
    'user_id'        // <--- CẨN THẬN: Kiểm tra xem DB có cột này chưa
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}