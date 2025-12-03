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
    Schema::table('users', function (Blueprint $table) {
        // Thêm cột mới, nhớ để nullable() để dữ liệu cũ không bị lỗi
        if (!Schema::hasColumn('users', 'gioi_tinh')) {
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable()->after('dia_chi');
        }
        if (!Schema::hasColumn('users', 'ngay_sinh')) {
            $table->date('ngay_sinh')->nullable()->after('gioi_tinh');
        }
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['gioi_tinh', 'ngay_sinh']);
    });
}

};
