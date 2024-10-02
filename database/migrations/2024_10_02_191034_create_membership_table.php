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
        
            CREATE TABLE memberships(
            
                membership_id INTEGER AUTO_INCREMENT PRIMARY KEY,
                membership_name VARCHAR(55) NOT NULL,
                price FLOAT NOT NULL
            
            )
        
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS memberships");
    }
};
