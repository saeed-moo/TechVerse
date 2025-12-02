<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@techverse.com',
            'password' => Hash::make('Admin123!'),
            'role' => 'admin',
            'phone' => '07700900000',
            'address' => '123 Tech Street',
            'city' => 'London',
            'postcode' => 'SW1A 1AA',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);

        // Test Customer
        User::create([
            'name' => 'John Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('Customer123!'),
            'role' => 'customer',
            'phone' => '07700900001',
            'address' => '456 User Avenue',
            'city' => 'Manchester',
            'postcode' => 'M1 1AA',
            'must_change_password' => false,
            'email_verified_at' => now(),
        ]);
    }
}
