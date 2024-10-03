<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("

            CREATE TABLE members_attendance (
            
                member_attendance_id INTEGER PRIMARY KEY AUTO_INCREMENT,
                member_id INT,
                FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
                member_name VARCHAR(255),
                attendance_status ENUM ('Present','Absent'),
                attendance_date DATE NOT NULL,
                check_in_time TIMESTAMP,
                check_out_time TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )


        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS members_attendance');
    }
};
