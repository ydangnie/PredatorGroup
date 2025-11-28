<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // QUAN TRỌNG: dùng $this->faker->unique() để tránh trùng slug
        $baseName = $this->faker->unique()->words(3, true) . ' ' . $this->faker->randomElement([
            'Automatic', 'Chronograph', 'Diver', 'GMT', 'Tourbillon',
            'Skeleton', 'Moonphase', 'Limited Edition', 'Heritage', 'Classic',
            'Swiss Made', 'Japanese Movement', 'Quartz Precision', 'Solar Power'
        ]);

        $hasVariations = $this->faker->boolean(75); // 75% có biến thể – thực tế shop đồng hồ

        return [
            'name'           => ucwords($baseName),
            'slug' => Str::slug($baseName) . '-' . fake()->unique()->uuid, // hoặc dùng số tăng dần
            'description'    => $this->faker->paragraph(3),
            'content'        => $this->faker->paragraphs(rand(4, 8), true),
            'price'          => $hasVariations ? null : $this->faker->numberBetween(2_500_000, 68_000_000),
            'stock'          => $hasVariations ? 0 : $this->faker->numberBetween(3, 150),
            'images'         => $this->generateImages(),
            'has_variations' => $hasVariations,
            'is_active'      => $this->faker->boolean(94),
            'category_id'    => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id'       => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'created_by'     => User::inRandomOrder()->first()?->id ?? 1,
        ];
    }

    private function generateImages(): array
    {
        $images = [];
        $count = $this->faker->numberBetween(4, 9);

        for ($i = 0; $i < $count; $i++) {
            $images[] = $this->faker->randomElement([
                // Placeholder đẹp
                'https://via.placeholder.com/1200x1200/1a1a1a/cccccc?text=' . ($i + 1),
                // Ảnh thật từ Flickr
                'https://loremflickr.com/1200/1200/luxury,watch?random=' . $i,
                // Picsum với seed cố định
                'https://picsum.photos/seed/watch' . ($i + 1000) . '/1200/1200',
                // Unsplash collection đồng hồ
                'https://source.unsplash.com/collection/190727/1200x1200?' . $i,
            ]);
        }

        return $images;
    }

    // Bonus: Tạo sản phẩm kèm biến thể luôn (dùng trong seeder)
    public function withVariations($count = null)
    {
        return $this->state(fn() => ['has_variations' => true])
                    ->afterCreating(function (Product $product) use ($count) {
                        \App\Models\ProductVariation::factory($count ?? rand(4, 12))->create([
                            'product_id' => $product->id,
                        ]);
                    });
    }
}
