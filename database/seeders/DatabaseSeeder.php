<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Forum;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
            'password' => 'password',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ForumSeeder::class,
            PlatformSeeder::class,
            ShareSeeder::class
        ]);
    }
}
