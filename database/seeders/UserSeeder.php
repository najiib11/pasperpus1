<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Pustakawan',
            'email' => 'pustakawan@gmail.com',
            'password' => bcrypt('password123'),
        ])->assignRole('pustakawan');

        User::create([
            'name' => 'User Anggota',
            'email' => 'anggota@gmail.com',
            'password' => bcrypt('password123'),
        ])->assignRole('anggota');
         User::create([
            'name' => 'User Anggota',
            'email' => 'anggota2@gmail.com',
            'password' => bcrypt('password123'),
        ])->assignRole('anggota');
    }
}
