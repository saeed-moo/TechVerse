<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test products page can be accessed
     */
    public function test_products_page_loads_successfully(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Products');
    }

    /**
     * Test product details page shows product
     */
    public function test_product_details_page_displays_product(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'name' => 'Test Laptop Pro',
            'slug' => 'test-laptop-pro',
            'price' => 1299.99,
        ]);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
        $response->assertSee('Test Laptop Pro');
        $response->assertSee('1,299.99'); // FIXED: Added comma to match number_format()
    }

    /**
     * Test authenticated user can add product to basket
     */
    public function test_authenticated_user_can_add_to_basket(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post("/basket/add/{$product->id}", [
                'quantity' => 1,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('baskets', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
