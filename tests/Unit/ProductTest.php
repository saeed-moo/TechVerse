<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test product creation
     */
    public function test_product_can_be_created(): void
    {
        $category = Category::factory()->create();

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test Laptop',
            'slug' => 'test-laptop',
            'description' => 'A test laptop for testing',
            'price' => 999.99,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
            'stock_status' => 'in_stock',
            'image' => 'test.jpg',
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Laptop', $product->name);
        $this->assertEquals(999.99, $product->price);
    }

    /**
     * Test product belongs to category
     */
    public function test_product_belongs_to_category(): void
    {
        $category = Category::factory()->create(['name' => 'Laptops']);
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals('Laptops', $product->category->name);
    }

    /**
     * Test stock decrement
     */
    public function test_product_stock_can_be_decremented(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 10]);

        $product->decrementStock(3);

        $this->assertEquals(7, $product->fresh()->stock_quantity);
    }

    /**
     * Test stock status updates
     */
    public function test_product_stock_status_updates_correctly(): void
    {
        $product = Product::factory()->create([
            'stock_quantity' => 0,
            'stock_status' => 'out_of_stock'
        ]);

        $this->assertEquals('out_of_stock', $product->stock_status);

        $product->update(['stock_quantity' => 10, 'stock_status' => 'in_stock']);

        $this->assertEquals('in_stock', $product->fresh()->stock_status);
    }

    /**
     * Test product price is numeric
     */
    public function test_product_price_is_numeric(): void
    {
        $product = Product::factory()->create(['price' => 599.99]);

        $this->assertIsNumeric($product->price);
        $this->assertEquals(599.99, $product->price);
    }
}
