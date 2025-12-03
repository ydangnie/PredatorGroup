<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id', 
        'name', 
        'phone', 
        'address', 
        'note', 
        'payment_method', 
        'total_price', // Đã sửa từ tong_tien -> total_price
        'status'       // Đã sửa từ trang_thai -> status
    ];

    public function items() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}