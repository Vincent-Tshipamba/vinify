<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'id' => 1, // Force l'ID pour lier ensuite l'université
                'name' => 'Admin UNIKIN',
                'email' => 'admin@unikin.cd',
                'password' => Hash::make('password'),
                'university_id' => null 
            ],
            [
                'id' => 2,
                'name' => 'Admin UNILU',
                'email' => 'admin@unilu.cd',
                'password' => Hash::make('password'),
                'university_id' => null
            ],
        ]);
    }
}
