<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'content',
        'excerpt',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'user_id' // Đã thêm cột này ở Bước 1
        // 'is_active' -> XÓA DÒNG NÀY VÌ DB KHÔNG CÓ
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}