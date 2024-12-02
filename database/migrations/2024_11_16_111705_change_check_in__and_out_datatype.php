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
        DB::statement("ALTER TABLE `members_attendance` MODIFY `check_in_time` TIME NULL;");
        DB::statement("ALTER TABLE `members_attendance` MODIFY `check_out_time` TIME NULL;");

        DB::statement("ALTER TABLE `trainers_attendance` MODIFY `check_in_time` TIME NULL;");
        DB::statement("ALTER TABLE `trainers_attendance` MODIFY `check_out_time` TIME NULL;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `members_attendance` MODIFY `check_in_time` TIMESTAMP NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `members_attendance` MODIFY `check_out_time` TIMESTAMP NULL DEFAULT NULL;");

        DB::statement("ALTER TABLE `trainers_attendance` MODIFY `check_in_time` TIMESTAMP NULL DEFAULT NULL;");
        DB::statement("ALTER TABLE `trainers_attendance` MODIFY `check_out_time` TIMESTAMP NULL DEFAULT NULL;");


    }
};
