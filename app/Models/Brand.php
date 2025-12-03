<?php

namespace App\Models;

// use Database\Factories\BrandFactory;
// use Illuminate\Database\Eloquent\Attributes\UseFactory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

// class Brand extends Model
// {

//     use HasFactory;
//     protected static function newFactory()
//     {
//         return BrandFactory::new();
//     }
//     protected $fillable = [
//         'name',
//         'slug',
//         'logo',         // có thể là URL hoặc đường dẫn file storage
//         'description',
//         'is_active',
//     ];

//     protected $casts = [
//         'is_active' => 'boolean',
//     ];

//     // Tự động tạo slug
//     protected static function boot()
//     {
//         parent::boot();

//         static::creating(function ($brand) {
//             $brand->slug = Str::slug($brand->name);
//         });

//         static::updating(function ($brand) {
//             if ($brand->isDirty('name')) {
//                 $brand->slug = Str::slug($brand->name);
//             }
//         });
//     }

//     // Quan hệ
//     public function products()
//     {
//         return $this->hasMany(Product::class);
//     }

//     // Route key bằng slug (đẹp cho URL)
//     public function getRouteKeyName()
//     {
//         return 'slug';
//     }

//     // Helper hiển thị logo (hỗ trợ URL hoặc file)
//     public function getLogoUrlAttribute()
//     {
//         if (!$this->logo) {
//             return asset('images/no-brand.png');
//         }

//         return filter_var($this->logo, FILTER_VALIDATE_URL)
//             ? $this->logo
//             : asset('storage/' . $this->logo);
//     }
// }
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    protected $fillable = [
        'ten_thuonghieu',
        'logo',
    ];
}
