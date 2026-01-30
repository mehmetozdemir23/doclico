<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        UserModel::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);
    }
}
