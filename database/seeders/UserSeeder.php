<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username'=> '0181919',
            'email' => 'admin@ifump.net',
            'password' => bcrypt('password')
        ]);
    }
}
