<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            " CREATE TABLE memberships (
            
                membership_id INT PRIMARY KEY AUTO_INCREMENT,
                membership_type ENUM('Standard', 'Premium') NOT NULL
            
            )
        
        "
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
