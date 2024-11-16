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
        // Add composite unique constraint using raw SQL
        DB::statement('ALTER TABLE `members_attendance` ADD UNIQUE `unique_member_attendance_date` (`member_id`, `attendance_date`)');
        DB::statement('ALTER TABLE `trainers_attendance` ADD UNIQUE `unique_trainer_attendance_date` (`trainer_id`, `attendance_date`)');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the composite unique constraint using raw SQL
        DB::statement('ALTER TABLE `members_attendance` DROP INDEX `unique_member_attendance_date`');
        DB::statement('ALTER TABLE `trainers_attendance` DROP INDEX `unique_trainer_attendance_date`');
    }
};
