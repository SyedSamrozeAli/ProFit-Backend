<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Add generated columns for payment_month and payment_year
        DB::statement("
            ALTER TABLE members_payments
            ADD COLUMN payment_month INT GENERATED ALWAYS AS (MONTH(payment_date)) STORED,
            ADD COLUMN payment_year INT GENERATED ALWAYS AS (YEAR(payment_date)) STORED
        ");

        // Add unique constraint on member_id, payment_month, and payment_year
        DB::statement("
            ALTER TABLE members_payments
            ADD CONSTRAINT unique_member_payment UNIQUE (member_id, payment_month, payment_year)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the unique constraint
        DB::statement("
            ALTER TABLE members_payments
            DROP INDEX unique_member_payment
        ");

        // Drop the generated columns
        DB::statement("
            ALTER TABLE members_payments
            DROP COLUMN payment_month,
            DROP COLUMN payment_year
        ");
    }
};
