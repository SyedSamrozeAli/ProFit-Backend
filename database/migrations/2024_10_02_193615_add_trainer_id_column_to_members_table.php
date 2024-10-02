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
        DB::statement("ALTER TABLE members ADD COLUMN trainer_id INT DEFAULT NULL AFTER DOB");

        //Adding foreign key constraint 
        DB::statement("ALTER TABLE members ADD CONSTRAINT fk_trainer FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE SET NULL");
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint
        DB::statement("ALTER TABLE members DROP FOREIGN KEY fk_trainer");

        // Drop trainer_id column from members table
        DB::statement("ALTER TABLE members DROP COLUMN trainer_id");
    }
};
