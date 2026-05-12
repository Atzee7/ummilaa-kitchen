<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ummilaakitchen.com'],
            [
                'name'     => 'Admin Ummilaa',
                'email'    => 'admin@ummilaakitchen.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );
    }
}