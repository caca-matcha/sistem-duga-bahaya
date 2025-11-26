<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        // User SHEs
        User::firstOrCreate(
            ['email' => 'she@example.com'],
            [
                'name' => 'SHE Admin',
                'password' => Hash::make('password'),
                'role' => 'she',
            ]
        );

        // User karyawan
        User::firstOrCreate(
            ['email' => 'karyawan@example.com'],
            [
                'name' => 'Karyawan Satu',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ]
        );
        
  // User karyawan
        User::firstOrCreate(
            ['email' => 'sasa@example.com'],
            [
                'name' => 'sasa',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ]
        );

    }
}
