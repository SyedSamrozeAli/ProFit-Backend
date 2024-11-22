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
        DB::statement("ALTER TABLE inventory_payments ADD COLUMN payment_reciept VARCHAR(255) DEFAULT NULL AFTER due_amount");
        DB::statement("ALTER TABLE expense ADD COLUMN payment_reciept VARCHAR(255) DEFAULT NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE inventory_payments DROP COLUMN payment_reciept");
        DB::statement("ALTER TABLE expense DROP COLUMN payment_reciept");

    }
};
