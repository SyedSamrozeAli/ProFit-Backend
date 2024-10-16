<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Rename the membership table
        DB::statement("RENAME TABLE membership TO member_has_membership");

        // Dropping the membership_id for members and members_payments tables
        DB::statement("ALTER TABLE members DROP FOREIGN KEY fk_membership");
        DB::statement("ALTER TABLE members DROP COLUMN membership_id");
        DB::statement("ALTER TABLE members_payments DROP FOREIGN KEY members_payments_ibfk_2");
        DB::statement("ALTER TABLE members_payments DROP COLUMN membership_id");

        // Drop primary key from the renamed table
        DB::statement("ALTER TABLE member_has_membership MODIFY membership_id INT NOT NULL");
        DB::statement("ALTER TABLE member_has_membership DROP PRIMARY KEY");

        // Dropping membership_id and membership_type from the renamed table
        DB::statement("ALTER TABLE member_has_membership DROP COLUMN membership_id");
        DB::statement("ALTER TABLE member_has_membership DROP COLUMN membership_type");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Renaming the table back
        DB::statement("RENAME TABLE member_has_membership TO membership");

        DB::statement("ALTER TABLE membership ADD membership_id INT AUTO_INCREMENT PRIMARY KEY");

        // Adding the membership_id for members and members_payments table
        DB::statement("ALTER TABLE members ADD membership_id INT");
        DB::statement("ALTER TABLE members ADD CONSTRAINT fk_membership FOREIGN KEY (membership_id) REFERENCES membership(membership_id)");
        DB::statement("ALTER TABLE members_payments ADD membership_id INT");
        DB::statement("ALTER TABLE members_payments ADD CONSTRAINT members_payments_membership_ibfk_2 FOREIGN KEY (membership_id) REFERENCES membership(membership_id)");

        // Now adding back membership_type
        DB::statement("ALTER TABLE membership ADD membership_type ENUM('Standard','Premium')");

    }
};