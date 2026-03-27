<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test order can be created
     */
    public function test_order_can_be_created(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST123',
            'total_amount' => 1500.00,
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => '123 Test St',
            'shipping_city' => 'London',
            'shipping_postcode' => 'SW1A 1AA',
            'contact_phone' => '07700900000',
        ]);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('ORD-TEST123', $order->order_number);
    }

    /**
     * Test order belongs to user
     */
    public function test_order_belongs_to_user(): void
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals('John Doe', $order->user->name);
    }

    /**
     * Test order has items
     */
    public function test_order_has_items(): void
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => 500.00,
            'subtotal' => 1000.00,
        ]);

        $this->assertCount(1, $order->items);
    }

    /**
     * Test order status can be updated
     */
    public function test_order_status_can_be_updated(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $order->update(['status' => 'processing']);

        $this->assertEquals('processing', $order->fresh()->status);
    }
}
