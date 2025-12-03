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
    Schema::create('banner', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->text('mota')->nullable();
        $table->string('hinhanh')->nullable();
        $table->string('thuonghieu')->nullable();
        $table->string('link')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            //
        });
    }
};
