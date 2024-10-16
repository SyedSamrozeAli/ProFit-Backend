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
        // Add a membership_id column to the members table
        DB::statement("ALTER TABLE members ADD membership_id INT AFTER member_email");

        // Add a foreign key constraint to the membership_id column in the members table
        // This constraint references the membership_id column in the memberships table
        DB::statement("ALTER TABLE members ADD CONSTRAINT members_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships(membership_id)");

        // Add a membership_id column to the members_payments table
        DB::statement("ALTER TABLE members_payments ADD membership_id INT AFTER member_id");

        // Add a foreign key constraint to the membership_id column in the members_payments table
        // This constraint references the membership_id column in the memberships table
        DB::statement("ALTER TABLE members_payments ADD CONSTRAINT members_payments_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships(membership_id)");

        // Add a membership_id column to the member_has_membership table
        DB::statement("ALTER TABLE member_has_membership ADD membership_id INT AFTER member_id");

        // Add a foreign key constraint to the membership_id column in the member_has_membership table
        // This constraint references the membership_id column in the memberships table
        DB::statement("ALTER TABLE member_has_membership ADD CONSTRAINT member_has_membership_fk FOREIGN KEY (membership_id) REFERENCES memberships(membership_id)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint from the members table
        DB::statement("ALTER TABLE members DROP FOREIGN KEY members_membership_fk");

        // Remove the membership_id column from the members table
        DB::statement("ALTER TABLE members DROP COLUMN membership_id");

        // Drop the foreign key constraint from the members_payments table
        DB::statement("ALTER TABLE members_payments DROP FOREIGN KEY members_payments_membership_fk");

        // Remove the membership_id column from the members_payments table
        DB::statement("ALTER TABLE members_payments DROP COLUMN membership_id");

        // Drop the foreign key constraint from the members_payments table
        DB::statement("ALTER TABLE member_has_membership DROP FOREIGN KEY member_has_membership_fk");

        // Remove the membership_id column from the members_payments table
        DB::statement("ALTER TABLE member_has_membership DROP COLUMN membership_id");
    }
};
