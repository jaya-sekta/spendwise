<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
=======
        // User::factory(10)->create();

>>>>>>> 3497b1b0449ac3de36406bec14c395d270bd056c
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
<<<<<<< HEAD

        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
            RewardSeeder::class, // <-- tambahkan ini
=======
        $this->call([
            AdminUserSeeder::class,
            CategorySeeder::class,
>>>>>>> 3497b1b0449ac3de36406bec14c395d270bd056c
        ]);
    }
}
