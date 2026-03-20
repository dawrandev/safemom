<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Uncomment to create test users
        // User::factory(10)->create();

        // Create a test admin user (optional)
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'surname' => 'User',
        //     'phone' => '+998901234567',
        //     'telegram_id' => '123456789',
        //     'role' => 'admin',
        // ]);
    }
}
