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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // $table->string('tensp');
            // $table->text('mota')->nullable();
            // $table->decimal('gia', 8, 2);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable(); // mô tả chi tiết (HTML)
            $table->decimal('price', 15, 2)->nullable();     // chỉ dùng khi KHÔNG có biến thể
            $table->integer('stock')->default(0);            // chỉ dùng khi KHÔNG có biến thể
            $table->json('images');                          // ["link1.jpg", "link2.jpg", ...]
            $table->boolean('has_variations')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
