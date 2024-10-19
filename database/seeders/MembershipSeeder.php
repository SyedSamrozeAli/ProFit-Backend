<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("INSERT INTO memberships (membership_type) VALUES ('Standard')");
        DB::statement("INSERT INTO memberships (membership_type) VALUES ('Premium')");
    }
}
