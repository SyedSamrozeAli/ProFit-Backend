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
        DB::statement("ALTER TABLE members DROP COLUMN membership_start_date");
        DB::statement("ALTER TABLE members DROP COLUMN membership_end_date");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE members ADD membership_start_date DATE");
        DB::statement("ALTER TABLE members ADD membership_end_date DATE");
    }
};
