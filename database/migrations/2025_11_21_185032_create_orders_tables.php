<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bảng đơn hàng (orders)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Thông tin giao hàng (Chuẩn tiếng Anh để khớp Controller)
            $table->string('name');             // Tên người nhận
            $table->string('phone');            // Số điện thoại
            $table->string('address');          // Địa chỉ giao hàng
            $table->text('note')->nullable();   // Ghi chú
            
            // Thông tin thanh toán
            $table->string('payment_method');   // cod, banking, vnpay
            $table->decimal('total_price', 15, 2); // Tổng tiền
            $table->string('status')->default('pending'); // Trạng thái: pending, paid, etc.
            
            $table->timestamps();
        });

        // 2. Bảng chi tiết đơn hàng (order_items)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            $table->string('product_name'); // Lưu tên SP lúc mua
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('total', 15, 2);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};