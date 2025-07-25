<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder user.
     */
    public function run(): void
    {
        User::create([
            'user_id'         => (string) Str::ulid(),
            'name'            => 'Admin',
            'username'        => 'admin',
            'email'           => 'admin@ifump.net',
            'password'        => Hash::make('password'),
            'membership_date' => now()->toDateString(), // default saat dibuat
        ]);
    }
}
