<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seedUsers = [
            [
                'name' => 'Lerma Magno',
                'email' => 'lermamagno12@gmail.com',
                'password' => Hash::make('TempPass123!'),
                'role' => 'hr-super-admin',
                'status' => 'active',
                'is_authorized' => true,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => 'active',
                'is_authorized' => true,
            ],
        ];

        foreach ($seedUsers as $seedUser) {
            User::updateOrCreate(
                ['email' => $seedUser['email']],
                $seedUser,
            );
        }
    }
}
