<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseFullSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Tao du lieu mau
     *  php artisan migrate:refresh --seed --seeder=DatabaseFullSeeder
     */
    public function run(): void
    {
        //
        \App\Models\User::factory()->create([
        'name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('123'),'role'=>'admin'
    ]);

    Brand::factory(25)->create();
    Category::factory(18)->create();

    // 60 sản phẩm không biến thể
    Product::factory(20)->create();

    // 40 sản phẩm có biến thể (mỗi cái 4–12 biến thể)
    Product::factory(5)->withVariations()->create();
    }
}
