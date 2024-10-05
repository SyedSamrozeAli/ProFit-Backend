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
        DB::statement("ALTER TABLE members ADD COLUMN membership_id INT AFTER member_email");
        DB::statement("ALTER TABLE members ADD CONSTRAINT fk_membership FOREIGN KEY (membership_id) REFERENCES membership(membership_id) ON DELETE SET NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE members DROP COLUMN membership_id");
    }
};
