<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    User::create([
            'name' => 'ali',
            'email' => 'ali@gmail.com',
            'password' => Hash::make('123321'),
            'role_id' => 2,
        ]);
    }
}
