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
        DB::statement("ALTER TABLE members_attendance DROP COLUMN member_name");
        DB::statement("ALTER TABLE trainers_attendance DROP COLUMN trainer_name");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE members_attendance ADD COLUMN member_name VARCHAR(255)");
        DB::statement("ALTER TABLE trainers_attendance ADD COLUMN trainer_name VARCHAR(255)");
    }
};
