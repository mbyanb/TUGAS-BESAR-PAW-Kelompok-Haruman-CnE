<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@siadu.com'],
            [
                'name' => 'Administrator',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create or update regular user
        User::updateOrCreate(
            ['email' => 'user@siadu.com'],
            [
                'name' => 'User Demo',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Create additional demo users if needed
        User::updateOrCreate(
            ['email' => 'john@siadu.com'],
            [
                'name' => 'John Doe',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'jane@siadu.com'],
            [
                'name' => 'Jane Smith',
                'phone' => '081234567893',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
