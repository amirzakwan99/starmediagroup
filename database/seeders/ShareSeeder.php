<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShareSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        for ($i = 0; $i < 6; $i++) {
            DB::table('shares')->insert([
                [
                    'user_id' => '1',
                    'forum_id' => '1',
                    'platform_id' => '1',
                    'platform_name' => 'Facebook',
                    'url' => 'http://localhost:8003/forum',
                    'ip_address' => '127.0.0.1',
                    'created_at' => '2026-04-23 13:08:22',
                    'updated_at' => '2026-04-23 13:08:22'
                ],
                [
                    'user_id' => '1',
                    'forum_id' => '1',
                    'platform_id' => '2',
                    'platform_name' => 'Twitter',
                    'url' => 'http://localhost:8003/forum',
                    'ip_address' => '127.0.0.1',
                    'created_at' => '2026-04-24 13:08:22',
                    'updated_at' => '2026-04-24 13:08:22'
                ],
                [
                    'user_id' => '1',
                    'forum_id' => '1',
                    'platform_id' => '3',
                    'platform_name' => 'WhatsApp',
                    'url' => 'http://localhost:8003/forum',
                    'ip_address' => '127.0.0.1',
                    'created_at' => '2026-04-25 13:08:22',
                    'updated_at' => '2026-04-25 13:08:22'
                ],
            ]);
        }
        
        for ($i = 0; $i < 4; $i++) {
            DB::table('shares')->insert([
                [
                    'user_id' => '1',
                    'forum_id' => '1',
                    'platform_id' => '4',
                    'platform_name' => 'Telegram',
                    'url' => 'http://localhost:8003/forum',
                    'ip_address' => '127.0.0.1',
                    'created_at' => '2026-04-24 13:08:22',
                    'updated_at' => '2026-04-24 13:08:22'
                ],
            ]);
        }

        DB::table('shares')->insert([
                [
                    'user_id' => '1',
                    'forum_id' => '1',
                    'platform_id' => '5',
                    'platform_name' => 'Email',
                    'url' => 'http://localhost:8003/forum',
                    'ip_address' => '127.0.0.1',
                    'created_at' => '2026-04-26 13:08:22',
                    'updated_at' => '2026-04-26 13:08:22'
                ],
            ]);
    }
}
