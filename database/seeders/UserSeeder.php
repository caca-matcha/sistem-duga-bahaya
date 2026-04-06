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
            ['npk' => '11240198'],
            [
                'name' => 'SHE Admin',
                'password' => Hash::make('password'),
                'role' => 'she',
            ]
        );

        // User karyawan
        User::firstOrCreate(
            ['npk' => 'karyawan'],
            [
                'name' => 'Karyawan Satu',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ]
        );
        
  // User karyawan
        User::firstOrCreate(
            ['npk' => 'sasa'],
            [
                'name' => 'sasa',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
            ]
        );

    }
}
