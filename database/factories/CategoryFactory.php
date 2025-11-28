<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    private static $categoryNames = [
        'Đồng hồ nam',
        'Đồng hồ nữ',
        'Smartwatch',
        'Đồng hồ đôi',
        'Đồng hồ cơ Automatic',
        'Đồng hồ Quartz',
        'Đồng hồ Chronograph',
        'Đồng hồ thể thao',
        'Đồng hồ cao cấp',
        'Đồng hồ thời trang',
        'Đồng hồ đôi tình nhân',
        'Đồng hồ chính hãng',
        'Đồng hồ Thụy Sĩ',
        'Đồng hồ Nhật Bản',
        'Đồng hồ chống nước',
        'Đồng hồ lặn Diver',
        'Đồng hồ Vintage',
        'Đồng hồ Skeleton',
    ];

    public function definition(): array
    {
        static $index = 0;
        $name = self::$categoryNames[$index++ % count(self::$categoryNames)];
        if ($index > count(self::$categoryNames)) {
            $name .= ' ' . ($index - count(self::$categoryNames));
        }

        return [
            'name'      => $name,
            'slug'      => Str::slug($name),
            'is_active' => true,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Category $category) {
            // CHỈ CHỌN CHA LÀ NHỮNG NODE KHÁC NÓ + ĐÃ TỒN TẠI TRƯỚC
            if ($this->faker->boolean(45) && Category::where('id', '!=', $category->id)->count() > 2) {
                $parent = Category::where('id', '!=', $category->id)
                    ->inRandomOrder()
                    ->first();

                if ($parent) {
                    // Kiểm tra thêm: không cho append vào con cháu của chính nó
                    if (!$category->isDescendantOf($parent) && !$parent->isDescendantOf($category)) {
                        $category->appendToNode($parent)->save();
                    }
                }
            }
        });
    }
}
