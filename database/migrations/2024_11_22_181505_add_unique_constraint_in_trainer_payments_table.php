<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add generated columns for payment_month and payment_year
        DB::statement("
            ALTER TABLE trainers_payments
            ADD COLUMN payment_month INT GENERATED ALWAYS AS (MONTH(payment_date)) STORED,
            ADD COLUMN payment_year INT GENERATED ALWAYS AS (YEAR(payment_date)) STORED
        ");

        // Add unique constraint on trainer_id, payment_month, and payment_year
        DB::statement("
            ALTER TABLE trainers_payments
            ADD CONSTRAINT unique_trainer_payment UNIQUE (trainer_id, payment_month, payment_year)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the unique constraint
        DB::statement("
            ALTER TABLE trainers_payments
            DROP INDEX unique_trainer_payment
        ");

        // Drop the generated columns
        DB::statement("
            ALTER TABLE trainers_payments
            DROP COLUMN payment_month,
            DROP COLUMN payment_year
        ");
    }
};
