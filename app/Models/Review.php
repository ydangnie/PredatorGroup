<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'so_sao',
        'binh_luan'
    ];

    // Liên kết với User (người bình luận)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Liên kết với Product (sản phẩm được bình luận)
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}