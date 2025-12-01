<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    
    // Khai báo tên bảng nếu cần (do tên model số nhiều)
    protected $table = 'products';

    protected $fillable = [
    'tensp', 
    'gender', // <--- Thêm dòng này
    'mota', 
    'gia', 
    'hinh_anh', 
    'so_luong', 
    'sku', 
    'category_id', 
    'brand_id'
];

    // Quan hệ với Category
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với Brand
    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    // Quan hệ với Variants
    public function variants() {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}