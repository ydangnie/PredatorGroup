<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    protected $fillable = [
        'name', 'slug', 'description', 'content', 'price', 'stock', 'images',
        'has_variations', 'is_active', 'category_id', 'brand_id', 'created_by'
    ];

    protected $casts = [
        'images' => 'array',
        'has_variations' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Quan hệ
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Giá thấp nhất để hiển thị ở danh sách
    public function getLowestPriceAttribute()
    {
        if (!$this->has_variations) {
            return $this->price;
        }
        return $this->variations()->min('price') ?? $this->price;
    }

    // Tổng tồn kho
    public function getTotalStockAttribute()
    {
        if (!$this->has_variations) {
            return $this->stock;
        }
        return $this->variations()->sum('stock');
    }

    // Tự động tạo slug + người tạo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
            $product->created_by = Auth::user()->id ?? 1;
        });
    }
}
