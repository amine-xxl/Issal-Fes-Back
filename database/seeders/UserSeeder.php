<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ahmed Client',
            'email' => 'client@test.com',
            'password' => Hash::make('password'), //creer un random mdp a partir de laravel.
            'role' => 'client',
            'active' => true,
        ]);

        User::create([
            'name' => 'Youssef Chauffeur',
            'email' => 'chauffeur@test.com',
            'password' => Hash::make('password'),
            'role' => 'chauffeur',
            'active' => true,
        ]);
    }
}
