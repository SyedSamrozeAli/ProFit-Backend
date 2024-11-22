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

        DB::statement("ALTER TABLE inventory_payments ADD COLUMN balance FLOAT AFTER due_amount");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE inventory_payments DROP COLUMN balance");
    }
};
