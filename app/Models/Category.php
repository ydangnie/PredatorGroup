<?php

namespace App\Models;

// use Database\Factories\CategoryFactory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Kalnoy\Nestedset\NodeTrait;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Str;

// class Category extends Model
// {
//     use NodeTrait;
//     use HasFactory;

//     protected static function newFactory()
//     {
//         return CategoryFactory::new();
//     }

//     protected $fillable = [
//         'name',
//         'slug',
//         'parent_id',
//         'is_active',
//     ];

//     protected $casts = [
//         'is_active' => 'boolean',
//     ];

//     // Tự động tạo slug khi tạo/sửa
//     protected static function boot()
//     {
//         parent::boot();

//         static::creating(function ($category) {
//             $category->slug = Str::slug($category->name);
//         });

//         static::updating(function ($category) {
//             if ($category->isDirty('name')) {
//                 $category->slug = Str::slug($category->name);
//             }
//         });
//     }

//     // Quan hệ
//     public function products()
//     {
//         return $this->hasMany(Product::class);
//     }

//     // Route key bằng slug thay vì id (đẹp cho URL)
//     public function getRouteKeyName()
//     {
//         return 'slug';
//     }

//     // Lấy tất cả danh mục con (dùng trong admin)
//     public function children()
//     {
//         return $this->hasMany(Category::class, 'parent_id')->with('children');
//     }
// }
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_danhmuc',
        'slug',
        'mota',
    ];
}
