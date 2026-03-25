<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $products = [
            // Laptops & Computers
            [
                'category' => 'Laptops & Computers',
                'name' => 'Dell XPS 15',
                'description' => 'High-performance 15.6" laptop with Intel i7, 16GB RAM, 512GB SSD. Perfect for professionals and students.',
                'price' => 1299.99,
                'stock' => 25,
            ],
            [
                'category' => 'Laptops & Computers',
                'name' => 'MacBook Air M2',
                'description' => 'Ultra-portable 13.6" laptop with M2 chip, 8GB RAM, 256GB SSD. Lightweight and powerful.',
                'price' => 1199.99,
                'stock' => 30,
            ],
            [
                'category' => 'Laptops & Computers',
                'name' => 'HP Pavilion Gaming Laptop',
                'description' => '15.6" gaming laptop with AMD Ryzen 7, 16GB RAM, RTX 3050, 512GB SSD.',
                'price' => 899.99,
                'stock' => 15,
            ],
            [
                'category' => 'Laptops & Computers',
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'description' => 'Business-class 14" laptop with Intel i7, 16GB RAM, 512GB SSD. Ultra-durable.',
                'price' => 1499.99,
                'stock' => 20,
            ],
            [
                'category' => 'Laptops & Computers',
                'name' => 'ASUS ROG Zephyrus',
                'description' => '15.6" gaming powerhouse with Intel i9, 32GB RAM, RTX 4070, 1TB SSD.',
                'price' => 2299.99,
                'stock' => 8,
            ],

            // Smartphones & Tablets
            [
                'category' => 'Smartphones & Tablets',
                'name' => 'iPhone 15 Pro',
                'description' => '256GB flagship smartphone with A17 Pro chip, titanium design, 48MP camera.',
                'price' => 999.99,
                'stock' => 40,
            ],
            [
                'category' => 'Smartphones & Tablets',
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => '256GB premium Android phone with S Pen, 200MP camera, AI features.',
                'price' => 1199.99,
                'stock' => 35,
            ],
            [
                'category' => 'Smartphones & Tablets',
                'name' => 'Google Pixel 8 Pro',
                'description' => '256GB smartphone with Google Tensor G3, exceptional camera, pure Android.',
                'price' => 899.99,
                'stock' => 25,
            ],
            [
                'category' => 'Smartphones & Tablets',
                'name' => 'iPad Pro 12.9"',
                'description' => 'M2-powered tablet with 256GB storage, Liquid Retina XDR display.',
                'price' => 1099.99,
                'stock' => 20,
            ],
            [
                'category' => 'Smartphones & Tablets',
                'name' => 'Samsung Galaxy Tab S9',
                'description' => '11" Android tablet with S Pen included, 128GB storage, AMOLED display.',
                'price' => 699.99,
                'stock' => 18,
            ],

            // Audio Equipment
            [
                'category' => 'Audio Equipment',
                'name' => 'Sony WH-1000XM5',
                'description' => 'Industry-leading noise canceling wireless headphones with 30hr battery.',
                'price' => 379.99,
                'stock' => 45,
            ],
            [
                'category' => 'Audio Equipment',
                'name' => 'AirPods Pro 2nd Gen',
                'description' => 'Premium wireless earbuds with active noise cancellation, spatial audio.',
                'price' => 249.99,
                'stock' => 60,
            ],
            [
                'category' => 'Audio Equipment',
                'name' => 'JBL Flip 6',
                'description' => 'Portable Bluetooth speaker with powerful sound, IP67 waterproof.',
                'price' => 129.99,
                'stock' => 50,
            ],
            [
                'category' => 'Audio Equipment',
                'name' => 'Bose QuietComfort 45',
                'description' => 'Premium noise canceling headphones with legendary comfort, 24hr battery.',
                'price' => 329.99,
                'stock' => 30,
            ],
            [
                'category' => 'Audio Equipment',
                'name' => 'Sennheiser Momentum 4',
                'description' => 'Audiophile wireless headphones with 60hr battery, exceptional sound.',
                'price' => 349.99,
                'stock' => 22,
            ],

            // Gaming & Accessories
            [
                'category' => 'Gaming & Accessories',
                'name' => 'PlayStation 5',
                'description' => 'Latest Sony gaming console with 825GB SSD, DualSense controller.',
                'price' => 479.99,
                'stock' => 12,
            ],
            [
                'category' => 'Gaming & Accessories',
                'name' => 'Xbox Series X',
                'description' => 'Microsoft\'s most powerful console with 1TB SSD, 4K gaming.',
                'price' => 449.99,
                'stock' => 15,
            ],
            [
                'category' => 'Gaming & Accessories',
                'name' => 'Nintendo Switch OLED',
                'description' => 'Portable gaming console with 7" OLED screen, 64GB storage.',
                'price' => 309.99,
                'stock' => 28,
            ],
            [
                'category' => 'Gaming & Accessories',
                'name' => 'Logitech G502 HERO',
                'description' => 'High-performance gaming mouse with 25K DPI sensor, 11 buttons.',
                'price' => 79.99,
                'stock' => 55,
            ],
            [
                'category' => 'Gaming & Accessories',
                'name' => 'Razer BlackWidow V3',
                'description' => 'Mechanical gaming keyboard with Green switches, RGB lighting.',
                'price' => 139.99,
                'stock' => 40,
            ],

            // Smart Home & Wearables
            [
                'category' => 'Smart Home & Wearables',
                'name' => 'Apple Watch Series 9',
                'description' => 'Advanced smartwatch with health tracking, always-on display, GPS.',
                'price' => 399.99,
                'stock' => 35,
            ],
            [
                'category' => 'Smart Home & Wearables',
                'name' => 'Samsung Galaxy Watch 6',
                'description' => 'Premium smartwatch with body composition analysis, 40hr battery.',
                'price' => 299.99,
                'stock' => 30,
            ],
            [
                'category' => 'Smart Home & Wearables',
                'name' => 'Amazon Echo Dot 5th Gen',
                'description' => 'Smart speaker with Alexa, improved sound, temperature sensor.',
                'price' => 49.99,
                'stock' => 100,
            ],
            [
                'category' => 'Smart Home & Wearables',
                'name' => 'Google Nest Hub Max',
                'description' => '10" smart display with Google Assistant, Nest Cam built-in.',
                'price' => 229.99,
                'stock' => 25,
            ],
            [
                'category' => 'Smart Home & Wearables',
                'name' => 'Fitbit Charge 6',
                'description' => 'Advanced fitness tracker with built-in GPS, heart rate monitoring.',
                'price' => 159.99,
                'stock' => 45,
            ],

            // Computer Accessories
            [
                'category' => 'Computer Accessories',
                'name' => 'Samsung 34" Curved Monitor',
                'description' => 'WQHD ultrawide monitor with 100Hz refresh rate, HDR10.',
                'price' => 449.99,
                'stock' => 18,
            ],
            [
                'category' => 'Computer Accessories',
                'name' => 'Logitech MX Master 3S',
                'description' => 'Premium wireless mouse with MagSpeed scrolling, 8K DPI.',
                'price' => 99.99,
                'stock' => 50,
            ],
            [
                'category' => 'Computer Accessories',
                'name' => 'Keychron K2 Wireless',
                'description' => 'Compact mechanical keyboard with hot-swappable switches, Mac/Win.',
                'price' => 89.99,
                'stock' => 35,
            ],
            [
                'category' => 'Computer Accessories',
                'name' => 'Seagate 2TB External HDD',
                'description' => 'Portable external hard drive with USB 3.0, plug-and-play.',
                'price' => 79.99,
                'stock' => 60,
            ],
            [
                'category' => 'Computer Accessories',
                'name' => 'Anker USB-C Hub 7-in-1',
                'description' => 'Multiport adapter with HDMI, USB 3.0, SD card reader, USB-C PD.',
                'price' => 49.99,
                'stock' => 70,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();

            Product::create([
                'category_id' => $category->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'image' => 'products/' . Str::slug($productData['name']) . '.jpg',
                'stock_quantity' => $productData['stock'],
                'low_stock_threshold' => 10,
                'stock_status' => $productData['stock'] > 10 ? 'in_stock' : 'low_stock',
                'is_active' => true,
            ]);
        }
        //
    DB::table('products')->insert([

     [
        'name' => 'Apple MacBook Pro 16" M3 Pro',
       //'category_id' => Categories::where('name', 'Laptops & Computers')->first()->id,
        'description' => 'High-end powerhouse: Stong CPU/GPU, ideal for heavy task like ......',
        'price' => 1295.00,
        'stock_quantity' => 'In stock',
        'image_path' => '',
        'featured' => false,
        'created_at' => now(),
        'updated_at' => now(),
     ]
    ]);
    }
}
