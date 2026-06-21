<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'POTI Admin',
            'email' => 'admin@poti.local',
            'role' => 'admin',
        ]);

        $cashier = User::factory()->create([
            'name' => 'Rahma Piket',
            'email' => 'piket@poti.local',
            'role' => 'piket',
        ]);

        $secondPiket = User::factory()->create([
            'name' => 'Nanda Piket',
            'email' => 'nanda@poti.local',
            'role' => 'piket',
        ]);

        Product::insert([
            [
                'name' => 'Nasi Goreng Special',
                'price' => 16000,
                'stock' => 24,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Ayam Bakso',
                'price' => 13000,
                'stock' => 18,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Es Teh Manis',
                'price' => 5000,
                'stock' => 65,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roti Bakar Cokelat',
                'price' => 8000,
                'stock' => 10,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schedule::create([
            'user_id' => $cashier->id,
            'date' => now()->toDateString(),
            'shift' => 'Pagi',
        ]);

        Schedule::create([
            'user_id' => $secondPiket->id,
            'date' => now()->addDay()->toDateString(),
            'shift' => 'Siang',
        ]);
    }
}
