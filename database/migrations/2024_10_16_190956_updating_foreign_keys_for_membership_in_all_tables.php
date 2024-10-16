<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {// Drop the existing foreign key constraint for membership_id in the members table
        DB::statement("ALTER TABLE members DROP FOREIGN KEY members_membership_fk");

        // Add the foreign key constraint back with ON DELETE SET NULL and ON UPDATE CASCADE
        DB::statement("ALTER TABLE members ADD CONSTRAINT members_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id) ON DELETE SET NULL ON UPDATE CASCADE");

        // Drop the existing foreign key constraint for membership_id in the member_has_membership table
        DB::statement("ALTER TABLE member_has_membership DROP FOREIGN KEY member_has_membership_fk");

        // Add the foreign key constraint back for member_has_membership with ON DELETE SET NULL and ON UPDATE CASCADE
        DB::statement("ALTER TABLE member_has_membership ADD CONSTRAINT member_has_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id) ON DELETE SET NULL ON UPDATE CASCADE");

        // Drop the existing foreign key constraint for membership_id in the members_payments table
        DB::statement("ALTER TABLE members_payments DROP FOREIGN KEY members_payments_membership_fk");

        // Add the foreign key constraint back for members_payments with ON DELETE SET NULL and ON UPDATE CASCADE
        DB::statement("ALTER TABLE members_payments ADD CONSTRAINT members_payments_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id) ON DELETE SET NULL ON UPDATE CASCADE");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting changes to the members table
        DB::statement("ALTER TABLE members DROP FOREIGN KEY members_membership_fk");
        DB::statement("ALTER TABLE members ADD CONSTRAINT members_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id)");

        // Reverting changes to the member_has_membership table
        DB::statement("ALTER TABLE member_has_membership DROP FOREIGN KEY member_has_membership_fk");
        DB::statement("ALTER TABLE member_has_membership ADD CONSTRAINT member_has_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id)");

        // Reverting changes to the members_payments table
        DB::statement("ALTER TABLE members_payments DROP FOREIGN KEY members_payments_membership_fk");
        DB::statement("ALTER TABLE members_payments ADD CONSTRAINT members_payments_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships (membership_id)");

    }
};
