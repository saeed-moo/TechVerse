<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Laptops & Computers',
                'Smartphones & Tablets',
                'Audio Equipment',
                'Gaming & Accessories',
                'Smart Home & Wearables',
                'Computer Accessories'
            ]),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
        ];
    }
}
