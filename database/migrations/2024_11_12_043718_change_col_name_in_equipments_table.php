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
        DB::statement("ALTER TABLE equipments DROP COLUMN desctiption");
        DB::statement("ALTER TABLE equipments ADD COLUMN description text");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE equipments DROP COLUMN description");
        DB::statement("ALTER TABLE equipments ADD COLUMN desctiption text");
    }
};
