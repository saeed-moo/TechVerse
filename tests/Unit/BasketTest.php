<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Basket;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test basket item can be created
     */
    public function test_basket_item_can_be_created(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $basket = Basket::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertInstanceOf(Basket::class, $basket);
        $this->assertEquals(2, $basket->quantity);
    }

    /**
     * Test basket belongs to user
     */
    public function test_basket_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $basket = Basket::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->assertEquals($user->id, $basket->user_id);
    }

    /**
     * Test basket quantity can be updated
     */
    public function test_basket_quantity_can_be_updated(): void
    {
        $basket = Basket::factory()->create(['quantity' => 1]);

        $basket->update(['quantity' => 5]);

        $this->assertEquals(5, $basket->fresh()->quantity);
    }
}
