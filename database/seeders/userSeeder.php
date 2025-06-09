<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // admin
        User::create([
            'firstname' => 'admin',
            'lastname' => 'owner',
            'username' => 'admin123',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // user biasa
         User::create([
            'firstname' => 'user',
            'lastname' => 'biasa',
            'username' => 'userbiasa',
            'password' => Hash::make('12345678'),
            'role' => 'user',
        ]);
    }
}
