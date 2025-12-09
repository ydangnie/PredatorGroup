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
    Schema::table('posts', function (Blueprint $table) {
        $table->string('slug')->unique()->after('title'); // URL thân thiện (vd: dong-ho-co-la-gi)
        $table->text('excerpt')->nullable()->after('slug'); // Đoạn trích ngắn hiển thị trên Google/List
        $table->string('meta_title')->nullable(); // Tiêu đề SEO
        $table->string('meta_desc')->nullable(); // Mô tả SEO
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
};
