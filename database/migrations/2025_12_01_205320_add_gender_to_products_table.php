<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Thêm cột gender sau cột tensp
            // Giá trị: male (Nam), female (Nữ), unisex (Cả hai)
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex')->after('tensp');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gender');
        });
    }
};