<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductSampleSeeder extends Seeder
{
    public function run()
    {
        // Tắt kiểm tra khóa ngoại để tránh lỗi khi xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Giả sử đã có category_id và brand_id từ 1 đến 5
        // Nếu chưa có, bạn hãy chạy Seeder cho Category/Brand trước hoặc để null
        
        $products = [
            [
                'tensp' => 'Áo Thun Azalman Basic Tee',
                'gender' => 'male',
                'gia' => 150000,
                'hinh_anh' => 'products/sample_tshirt.jpg', // Bạn cần có ảnh này trong storage/app/public/products hoặc để demo
                'sku' => 'TS001',
                'mota' => 'Áo thun cotton 100% thấm hút mồ hôi, co giãn 4 chiều.',
                'category_id' => 1, 
                'brand_id' => 1,
                'variants' => [
                    ['size' => 'M', 'color' => 'Trắng', 'stock' => 50],
                    ['size' => 'L', 'color' => 'Trắng', 'stock' => 30],
                    ['size' => 'M', 'color' => 'Đen', 'stock' => 40],
                    ['size' => 'XL', 'color' => 'Đen', 'stock' => 20],
                ]
            ],
            [
                'tensp' => 'Quần Jean Slimfit Ripped',
                'gender' => 'male',
                'gia' => 450000,
                'hinh_anh' => 'products/sample_jeans.jpg',
                'sku' => 'JN002',
                'mota' => 'Quần Jean dáng ôm, wash rách gối cá tính.',
                'category_id' => 2,
                'brand_id' => 1,
                'variants' => [
                    ['size' => '29', 'color' => 'Xanh Nhạt', 'stock' => 15],
                    ['size' => '30', 'color' => 'Xanh Nhạt', 'stock' => 20],
                    ['size' => '31', 'color' => 'Xanh Đậm', 'stock' => 25],
                ]
            ],
            [
                'tensp' => 'Váy Đầm Hoa Nhí Vintage',
                'gender' => 'female',
                'gia' => 320000,
                'hinh_anh' => 'products/sample_dress.jpg',
                'sku' => 'DR003',
                'mota' => 'Đầm voan hoa nhí nhẹ nhàng, phù hợp đi dạo phố.',
                'category_id' => 3,
                'brand_id' => 2,
                'variants' => [
                    ['size' => 'S', 'color' => 'Hồng', 'stock' => 10],
                    ['size' => 'M', 'color' => 'Hồng', 'stock' => 15],
                ]
            ],
            [
                'tensp' => 'Giày Sneaker Azalman Chunky',
                'gender' => 'unisex',
                'gia' => 850000,
                'hinh_anh' => 'products/sample_sneaker.jpg',
                'sku' => 'SN004',
                'mota' => 'Giày đế độn 5cm, phong cách Hàn Quốc năng động.',
                'category_id' => 4,
                'brand_id' => 1,
                'variants' => [
                    ['size' => '38', 'color' => 'Trắng/Xám', 'stock' => 5],
                    ['size' => '39', 'color' => 'Trắng/Xám', 'stock' => 8],
                    ['size' => '40', 'color' => 'Trắng/Xám', 'stock' => 12],
                    ['size' => '41', 'color' => 'Trắng/Xám', 'stock' => 10],
                ]
            ],
            [
                'tensp' => 'Áo Khoác Bomber Varsity',
                'gender' => 'unisex',
                'gia' => 550000,
                'hinh_anh' => 'products/sample_jacket.jpg',
                'sku' => 'JK005',
                'mota' => 'Áo khoác bóng chày chất nỉ ngoại, lót dù bên trong.',
                'category_id' => 1,
                'brand_id' => 2,
                'variants' => [
                    ['size' => 'L', 'color' => 'Xanh/Trắng', 'stock' => 20],
                    ['size' => 'XL', 'color' => 'Xanh/Trắng', 'stock' => 15],
                    ['size' => 'L', 'color' => 'Đen/Trắng', 'stock' => 25],
                ]
            ],
        ];

        foreach ($products as $prodData) {
            // Tách mảng variants ra khỏi mảng sản phẩm chính
            $variants = $prodData['variants'];
            unset($prodData['variants']);

            // Thêm timestamps
            $prodData['created_at'] = Carbon::now();
            $prodData['updated_at'] = Carbon::now();

            // Insert sản phẩm và lấy ID
            $productId = DB::table('products')->insertGetId($prodData);

            // Insert variants
            foreach ($variants as $variant) {
                $variant['product_id'] = $productId;
                $variant['created_at'] = Carbon::now();
                $variant['updated_at'] = Carbon::now();
                DB::table('product_variants')->insert($variant);
            }
        }
    }
}