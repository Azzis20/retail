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
            'contact' => '09325794034',
            'email' => 'm.capro@email.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);
        User::create([
            'fname' => 'George',
            'lname' => 'Francia',
            'address' => 'Bunawan Dist',
            'contact' => '09127654321',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
         User::create([
            'fname' => 'Elana',
            'lname' => 'Fablo',
            'address' => 'Bunawan Dist',
            'contact' => '09983675342',
            'email' => 'staff@company.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);
    }
}



