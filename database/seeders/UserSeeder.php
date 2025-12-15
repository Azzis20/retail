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
        User::create([
            'fname' => 'Miguel',
            'lname' => 'Capro',
            'address' => 'Bunawan Dist',
            'contact' => '09987654321',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);
        User::create([
            'fname' => 'John',
            'lname' => 'Cena',
            'address' => 'Bunawan Dist',
            'contact' => '09987654321',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}



