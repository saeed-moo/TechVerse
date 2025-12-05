<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
