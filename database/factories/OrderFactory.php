<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper($this->faker->bothify('???###')),
            'total_amount' => $this->faker->randomFloat(2, 50, 3000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered']),
            'payment_status' => 'paid',
            'payment_method' => 'stripe',
            'shipping_address' => $this->faker->streetAddress(),
            'shipping_city' => $this->faker->city(),
            'shipping_postcode' => $this->faker->postcode(),
            'contact_phone' => $this->faker->phoneNumber(),
        ];
    }
}
