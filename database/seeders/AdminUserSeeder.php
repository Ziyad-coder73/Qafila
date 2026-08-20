<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@qafilainsurance.com'],
            [
                'name' => 'Qafila Admin',
                'password' => Hash::make('Qafila@2026'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
