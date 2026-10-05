<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add two specific users for testing
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        // Add 12 more users to test pagination (needs > 10)
        \App\Models\User::factory(12)->create();
    }
}
