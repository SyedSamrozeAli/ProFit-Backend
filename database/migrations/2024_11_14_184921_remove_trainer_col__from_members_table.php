<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Drop the foreign key constraint
        DB::statement("ALTER TABLE members DROP FOREIGN KEY fk_trainer");

        // Drop the trainer_id column
        DB::statement("ALTER TABLE members DROP COLUMN trainer_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Add back the trainer_id column
        DB::statement("ALTER TABLE members ADD COLUMN trainer_id INT");

        // Add the foreign key constraint back to trainer_id
        DB::statement("ALTER TABLE members ADD CONSTRAINTfk_trainer FOREIGN KEY (trainer_id) REFERENCES trainers(trainer_id) ON DELETE SET NULL ON UPDATE CASCADE;");
    }
};
