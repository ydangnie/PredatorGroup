<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Khai báo các cột được phép lưu vào database
    protected $fillable = [
        'tensp', 
        'gender', 
        'mota', 
        'gia', 
        'so_luong', // <--- QUAN TRỌNG: Phải có cột này
        'hinh_anh', 
        'sku', 
        'category_id', 
        'brand_id'
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function variants() {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}