<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateInternAccounts extends Command
{
    protected $signature = 'app:create-intern-accounts';

    protected $description = 'Create 10 intern accounts for testing';

    public function handle()
    {
        $this->info('Creating intern accounts...');

        for ($i = 1; $i <= 10; $i++) {
            $npk = 'MAGANG'.str_pad($i, 4, '0', STR_PAD_LEFT);
            User::updateOrCreate(
                ['npk' => $npk],
                [
                    'name' => 'Intern '.$i,
                    'password' => Hash::make($npk),
                    'role' => 'magang',
                    'department' => 'Testing',
                    'position' => 'Internship',
                ]
            );
            $this->line("Created/Updated: $npk");
        }

        $this->info('All intern accounts created successfully!');
    }
}
