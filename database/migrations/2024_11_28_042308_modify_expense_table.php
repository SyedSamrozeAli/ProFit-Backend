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
        DB::statement("ALTER TABLE expense ADD COLUMN due_date DATE AFTER expense_date");
        DB::statement("ALTER TABLE expense ADD COLUMN payment_amount decimal(10,2) AFTER expense_category ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE expense DROP COLUMN payment_amount");
        DB::statement("ALTER TABLE expense DROP COLUMN due_date");
    }
};
