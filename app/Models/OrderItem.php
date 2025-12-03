<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'product_name', 'quantity', 'price', 'total'];

    // Quan hệ ngược về Product (để lấy ảnh)
    public function product() {
        return $this->belongsTo(Products::class, 'product_id');
    }
}