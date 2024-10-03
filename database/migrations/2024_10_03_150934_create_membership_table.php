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
        
            CREATE TABLE membership (
                membership_id INT PRIMARY KEY AUTO_INCREMENT,
                membership_type ENUM('Standard','Premium') DEFAULT 'Standard' NOT NULL,
                member_id INT NOT NULL,
                CONSTRAINT fk_membership_member FOREIGN KEY (member_id) REFERENCES members (member_id) ON DELETE CASCADE ON UPDATE CASCADE,
                price FLOAT NOT NULL,
                UNIQUE (member_id, membership_id),
                duration ENUM('3','6','12') DEFAULT 3,
                start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                end_date TIMESTAMP,
                status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS membership");
    }
};
