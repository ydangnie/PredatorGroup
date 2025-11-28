<?php

namespace App\Models;

use Database\Factories\ProductVariationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ProductVariationFactory::new();
    }

    protected $fillable = [
        'product_id',
        'option1',
        'option2',
        'option3',
        'price',
        'stock',
        'image',
        'sku'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Hiển thị tên biến thể đẹp: Vàng - 42mm - Dây da bò
    public function getDisplayNameAttribute()
    {
        $options = collect([$this->option1, $this->option2, $this->option3])
            ->filter()
            ->implode(' - ');

        return $options ?: 'Mặc định';
    }

    // Tự sinh SKU đẹp: SP001-VANG-42-DA
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($v) {
            if (!$v->sku && $v->product) {
                $code = 'SP' . str_pad($v->product->id, 4, '0', STR_PAD_LEFT);
                $o1 = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $v->option1 ?? 'X'), 0, 3)); // chỉ lấy chữ cái
                $o2 = preg_replace('/[^0-9]/', '', $v->option2 ?? ''); // chỉ lấy số
                $o3 = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $v->option3 ?? 'X'), 0, 2));
                $v->sku = "$code-$o1$o2$o3";
            }
        });
    }
}
