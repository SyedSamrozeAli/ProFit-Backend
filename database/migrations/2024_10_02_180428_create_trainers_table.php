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
        
            CREATE TABLE trainers (

                trainer_id INT AUTO_INCREMENT PRIMARY KEY, 
                trainer_name VARCHAR(55) NOT NULL,
                trainer_email VARCHAR(55) UNIQUE NOT NULL,
                CNIC VARCHAR(13) NOT NULL,
                age INT NOT NULL,
                gender ENUM('male','female') NOT NULL,
                DOB DATE NOT NULL,
                phone_number VARCHAR(11) NOT NULL,
                trainer_profile_image VARCHAR(255) DEFAULT NULL, 
                trainer_address TEXT,
                experience INT DEFAULT 0,
                salary FLOAT NOT NULL,
                hourly_rate FLOAT NOT NULL,
                availability BOOL DEFAULT TRUE,
                hire_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                rating DECIMAL(3,1)

            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS trainers");
    }
};
