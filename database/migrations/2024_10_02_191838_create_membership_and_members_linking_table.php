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
        
            CREATE TABLE members_have_membership (
                member_id INT NOT NULL,
                membership_id INT NOT NULL,
                FOREIGN KEY (member_id) REFERENCES members (member_id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (membership_id) REFERENCES memberships (membership_id) ON DELETE CASCADE ON UPDATE CASCADE,
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
        DB::statement('DROP TABLE IF EXISTS members_have_membership');
    }
};
