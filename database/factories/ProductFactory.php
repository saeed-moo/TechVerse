<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 100, 2000);
        $stock = $this->faker->numberBetween(0, 50);

        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'stock_quantity' => $stock,
            'low_stock_threshold' => 10,
            'stock_status' => $stock > 10 ? 'in_stock' : ($stock > 0 ? 'low_stock' : 'out_of_stock'),
            'image' => 'default.jpg',
        ];
    }
}
