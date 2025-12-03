<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // Mã giảm giá (VD: SALE50)
        $table->string('type')->default('fixed'); // Loại: 'fixed' (tiền) hoặc 'percent' (%)
        $table->decimal('value', 10, 2); // Giá trị giảm
        $table->integer('quantity')->default(0); // Số lượng mã
        $table->dateTime('start_date')->nullable(); // Ngày bắt đầu
        $table->dateTime('end_date')->nullable(); // Ngày kết thúc
        $table->boolean('status')->default(true); // Trạng thái: 1=Hiện, 0=Ẩn
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
