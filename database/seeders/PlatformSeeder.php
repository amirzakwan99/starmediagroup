<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        DB::table('platforms')->insert([
            [
                'name' => 'Facebook'
            ],
            [
                'name' => 'Twitter'
            ],
            [
                'name' => 'WhatsApp'
            ],
            [
                'name' => 'Telegram'
            ],
            [
                'name' => 'Email'
            ]
        ]);
    }
}
