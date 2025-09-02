<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SubscriptionSeeder::class,
            UserSeeder::class,
            UniversitySeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        // Mise à jour des users avec leur université
        User::where('id', 1)->update(['university_id' => 1]);
        User::where('id', 2)->update(['university_id' => 2]);

    }
}
