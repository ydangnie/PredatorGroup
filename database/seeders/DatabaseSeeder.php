<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        // User::factory(10)->create();

        User::factory(1)->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('123'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
