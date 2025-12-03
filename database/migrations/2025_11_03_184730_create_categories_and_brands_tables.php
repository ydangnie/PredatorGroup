<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_categories_and_brands_tables.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bảng Danh mục
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danhmuc');
            $table->string('slug')->nullable(); // URL thân thiện
            $table->text('mota')->nullable();
            $table->timestamps();
            // $table->string('name');
            // $table->string('slug')->unique();
            // $table->unsignedBigInteger('parent_id')->nullable();
            // $table->integer('_lft')->default(0);
            // $table->integer('_rgt')->default(0);
            // $table->integer('depth')->default(0);
            // $table->boolean('is_active')->default(true);
            // $table->timestamps();

            // $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            // $table->index(['_lft', '_rgt']);
        });

        // Bảng Thương hiệu
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('ten_thuonghieu');
            $table->string('logo')->nullable();
            // $table->string('name');
            // $table->string('slug')->unique();
            // $table->string('logo')->nullable();        // có thể là URL hoặc file
            // $table->text('description')->nullable();
            // $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};
