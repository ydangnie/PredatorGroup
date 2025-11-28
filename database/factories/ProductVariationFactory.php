<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariationFactory extends Factory
{
    protected $model = ProductVariation::class;

    public function definition(): array
    {
        $colors = ['Black', 'Silver', 'Gold', 'Blue', 'White', 'Rose Gold', 'Green'];
        $sizes  = ['38mm', '40mm', '42mm', '44mm', '46mm'];
        $straps = ['Leather', 'Steel', 'Rubber', 'NATO', 'Mesh'];

        $option1 = $this->faker->randomElement($colors);
        $option2 = $this->faker->randomElement($sizes);
        $option3 = $this->faker->randomElement($straps);

        return [
            'product_id' => Product::where('has_variations', true)->inRandomOrder()->first() ?? Product::factory(),
            'option1'    => $this->faker->randomElement(['Đen', 'Bạc', 'Vàng', 'Xanh Navy', 'Trắng', 'Vàng Hồng']), // vẫn để tiếng Việt để hiển thị đẹp
            'option2'    => $option2,
            'option3'    => $this->faker->randomElement(['Dây da', 'Dây thép', 'Dây cao su', 'Dây vải NATO', 'Dây lưới']).$this->faker->unique()->uuid(),
            'price'      => $this->faker->numberBetween(3000000, 80000000),
            'stock'      => $this->faker->numberBetween(0, 50),
            'image'      => $this->faker->randomElement([
                'https://via.placeholder.com/800x800/333333/ffffff?text=' . substr($option1, 0, 3),
                'https://loremflickr.com/800/800/watch',
                null,
            ]),
            // QUAN TRỌNG: chỉ dùng ký tự ASCII trong SKU
            'sku' => 'SP' . str_pad(rand(1,9999), 4, '0', STR_PAD_LEFT)
                     . '-' . strtoupper(substr($option1, 0, 3))
                     . '-' . $option2
                     . '-' . strtoupper(substr(explode(' ', $option3)[0] ?? 'X', 0, 2)),
        ];
    }
}
