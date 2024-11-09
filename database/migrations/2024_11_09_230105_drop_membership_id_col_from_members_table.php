<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::statement('ALTER TABLE members DROP FOREIGN KEY members_membership_fk;');
        DB::statement('ALTER TABLE members DROP COLUMN membership_id;');
    }

    public function down()
    {
        DB::statement('ALTER TABLE members ADD COLUMN membership_id BIGINT UNSIGNED NULL;');
        DB::statement('ALTER TABLE members ADD CONSTRAINT members_membership_fk FOREIGN KEY (membership_id) REFERENCES membership(membership_id) ON DELETE CASCADE;');
    }
};
