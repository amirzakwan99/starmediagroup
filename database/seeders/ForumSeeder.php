<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForumSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        DB::table('forums')->insert([
            [
                'content' => '<p style="text-align: center;"><span style="font-size: 23px;"><em><span style="background-color: #fbeeb8;"><strong>HELLO, WORLD!</strong></span></em></span></p>'
            ]
        ]);
    }
}
