<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pengaduanku.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create regular user
        User::create([
            'name' => 'User Demo',
            'email' => 'user@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '081234567891',
        ]);

        // Create sample reports
        Report::create([
            'title' => 'Jalan Rusak di Jl. Sudirman',
            'description' => 'Jalan berlubang besar yang membahayakan pengendara',
            'category' => 'Infrastruktur',
            'location' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'status' => 'in_progress',
            'user_id' => 2,
        ]);

        Report::create([
            'title' => 'Lampu Jalan Mati',
            'description' => 'Lampu jalan di depan sekolah sudah mati 3 hari',
            'category' => 'Fasilitas Umum',
            'location' => 'Jl. Pendidikan No. 45, Jakarta Selatan',
            'status' => 'pending',
            'user_id' => 2,
        ]);
    }
}