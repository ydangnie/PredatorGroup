<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('option1')->nullable();  // Màu sắc (Vàng, Bạc, Đen...)
            $table->string('option2')->nullable();  // Size (38mm, 40mm, 42mm...)
            $table->string('option3')->nullable();  // Chất liệu dây (Da bò, Thép, Vải NATO...)

            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->text('image')->nullable();    // ảnh riêng của biến thể
            $table->string('sku')->unique()->nullable(); // tự sinh nếu muốn

            // Không cho trùng kết hợp 3 option
            $table->unique(['product_id', 'option1', 'option2', 'option3']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
