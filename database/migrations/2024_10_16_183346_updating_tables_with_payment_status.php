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
        // Drop the `expense_status` column (ENUM) from the `expense` table
        DB::statement("ALTER TABLE expense DROP COLUMN expense_status");

        // Add a new `expense_status` column as an INT to the `expense` table
        DB::statement("ALTER TABLE expense ADD expense_status INT AFTER amount");

        // Add a foreign key constraint on the new `expense_status` column, linking it to `payment_status_id` in `payment_status` table
        DB::statement("ALTER TABLE expense ADD CONSTRAINT expense_status_fk FOREIGN KEY (expense_status) REFERENCES payment_status(payment_status_id) ON UPDATE CASCADE ON DELETE SET NULL");

        // Drop the `status` column from the `inventory_payments` table (ENUM)
        DB::statement("ALTER TABLE inventory_payments DROP COLUMN status");

        // Add a new `payment_status` column as an INT to the `inventory_payments` table
        DB::statement("ALTER TABLE inventory_payments ADD payment_status INT AFTER amount_paid");

        // Add a foreign key constraint on the new `payment_status` column, linking it to `payment_status_id` in `payment_status` table
        DB::statement("ALTER TABLE inventory_payments ADD CONSTRAINT inventory_payment_status_fk FOREIGN KEY (payment_status) REFERENCES payment_status(payment_status_id) ON UPDATE CASCADE ON DELETE SET NULL");

        // Drop the existing `payment_status` column from the `members_payments` table (ENUM)
        DB::statement("ALTER TABLE members_payments DROP COLUMN payment_status");

        // Add a new `payment_status` column as an INT to the `members_payments` table
        DB::statement("ALTER TABLE members_payments ADD payment_status INT AFTER payment_amount");

        // Add a foreign key constraint on the new `payment_status` column, linking it to `payment_status_id` in `payment_status` table
        DB::statement("ALTER TABLE members_payments ADD CONSTRAINT members_payment_status_fk FOREIGN KEY (payment_status) REFERENCES payment_status(payment_status_id) ON UPDATE CASCADE ON DELETE SET NULL");

        // Drop the existing `payment_status` column from the `trainers_payments` table (ENUM)
        DB::statement("ALTER TABLE trainers_payments DROP COLUMN payment_status");

        // Add a new `payment_status` column as an INT to the `trainers_payments` table
        DB::statement("ALTER TABLE trainers_payments ADD payment_status INT AFTER payment_amount");

        // Add a foreign key constraint on the new `payment_status` column, linking it to `payment_status_id` in `payment_status` table
        DB::statement("ALTER TABLE trainers_payments ADD CONSTRAINT trainers_payment_status_fk FOREIGN KEY (payment_status) REFERENCES payment_status(payment_status_id) ON UPDATE CASCADE ON DELETE SET NULL");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes in the `expense` table
        DB::statement("ALTER TABLE expense DROP FOREIGN KEY expense_status_fk");
        DB::statement("ALTER TABLE expense DROP COLUMN expense_status");
        DB::statement("ALTER TABLE expense ADD expense_status ENUM('pending', 'paid', 'failed')");

        // Reverse the changes in the `inventory_payments` table
        DB::statement("ALTER TABLE inventory_payments DROP FOREIGN KEY inventory_payment_status_fk");
        DB::statement("ALTER TABLE inventory_payments DROP COLUMN payment_status");
        DB::statement("ALTER TABLE inventory_payments ADD status ENUM('pending', 'paid', 'failed')");

        // Reverse the changes in the `members_payments` table
        DB::statement("ALTER TABLE members_payments DROP FOREIGN KEY members_payment_status_fk");
        DB::statement("ALTER TABLE members_payments DROP COLUMN payment_status");
        DB::statement("ALTER TABLE members_payments ADD payment_status ENUM('pending', 'paid', 'failed')");

        // Reverse the changes in the `trainers_payments` table
        DB::statement("ALTER TABLE trainers_payments DROP FOREIGN KEY trainers_payment_status_fk");
        DB::statement("ALTER TABLE trainers_payments DROP COLUMN payment_status");
        DB::statement("ALTER TABLE trainers_payments ADD payment_status ENUM('pending', 'paid', 'failed')");
    }

};
