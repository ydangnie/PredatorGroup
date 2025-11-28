<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company . ' Watches';

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'logo'        => $this->faker->randomElement([
                'https://via.placeholder.com/200x100/1a1a1a/ffffff?text=' . urlencode($name),
                'https://logo.clearbit.com/' . strtolower(str_replace(' ', '', $name)) . '.com',
                null,
            ]),
            'description' => $this->faker->paragraph(3),
            'is_active'   => $this->faker->boolean(95),
        ];
    }
}
