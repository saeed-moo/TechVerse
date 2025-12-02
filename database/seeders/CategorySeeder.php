<?php

namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $categories = [
            [
                'name' => 'Laptops & Computers',
                'description' => 'High-performance laptops and desktop computers for work, study, and gaming',
                'icon' => 'laptop',
            ],
            [
                'name' => 'Smartphones & Tablets',
                'description' => 'Latest smartphones and tablets from top brands',
                'icon' => 'smartphone',
            ],
            [
                'name' => 'Audio Equipment',
                'description' => 'Premium headphones, earbuds, and speakers',
                'icon' => 'headphones',
            ],
            [
                'name' => 'Gaming & Accessories',
                'description' => 'Gaming consoles, controllers, and gaming peripherals',
                'icon' => 'gamepad2',
            ],
            [
                'name' => 'Smart Home & Wearables',
                'description' => 'Smart watches, fitness trackers, and home automation devices',
                'icon' => 'watch',
            ],
            [
                'name' => 'Computer Accessories',
                'description' => 'Monitors, keyboards, mice, and storage solutions',
                'icon' => 'mouse',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => 'categories/' . Str::slug($category['name']) . '.jpg',
                'icon' => $category['icon'],  // THIS WAS MISSING!
            ]);
        }
    }
}
