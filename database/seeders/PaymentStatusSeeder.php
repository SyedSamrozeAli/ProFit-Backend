<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert("INSERT INTO payment_status (payment_status_id,status_name,status_description)
                    VALUES (1,'completed','payment have been completed')");
        DB::insert("INSERT INTO payment_status (payment_status_id,status_name,status_description)
                    VALUES (2,'pending','payment is pending')");
        DB::insert("INSERT INTO payment_status (payment_status_id,status_name,status_description)
                    VALUES (3,'failed','payment have been failed')");
    }
}
