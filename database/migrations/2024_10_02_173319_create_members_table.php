<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("

                CREATE TABLE members (

                    member_id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(55) NOT NULL,
                    member_email VARCHAR(55) UNIQUE NOT NULL,
                    phone_number VARCHAR(11) UNIQUE,
                    address VARCHAR(255),
                    age INT NOT NULL,
                    CNIC VARCHAR(13) UNIQUE,
                    DOB DATE NOT NULL,
                    height INT NOT NULL DEFAULT 0,
                    weight INT NOT NULL DEFAULT 0,
                    bmi DECIMAL(5, 2),
                    membership_type ENUM('Standard', 'Premium') NOT NULL,
                    profile_image VARCHAR(255),
                    health_issues TEXT DEFAULT NULL,
                    user_status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
                    addmission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    membership_start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    membership_end_date TIMESTAMP NULL

                )
            
            ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS members");
    }
};
