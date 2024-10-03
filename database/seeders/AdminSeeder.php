<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = bcrypt('123');

         DB::statement("
            INSERT INTO admin (username, email, password, created_at, updated_at)
            VALUES ('admin', 'abc@gmail.com', '$password', NOW(), NOW())
        ");
    }
}
