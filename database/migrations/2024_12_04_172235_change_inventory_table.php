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
        DB::statement('ALTER TABLE `inventory_payments` MODIFY COLUMN `inventory_id` INT NULL');

        DB::statement('ALTER TABLE `inventory_payments` DROP FOREIGN KEY `inventory_payments_ibfk_1`');

        DB::statement('ALTER TABLE `inventory_payments` 
            ADD CONSTRAINT `inventory_payments_ibfk_1` 
            FOREIGN KEY (`inventory_id`) 
            REFERENCES `inventory` (`inventory_id`) 
            ON DELETE SET NULL 
            ON UPDATE CASCADE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `inventory_payments` MODIFY COLUMN `inventory_id` INT NOT NULL');

        DB::statement('ALTER TABLE `inventory_payments` DROP FOREIGN KEY `inventory_payments_ibfk_1`');

        DB::statement('ALTER TABLE `inventory_payments` 
            ADD CONSTRAINT `inventory_payments_ibfk_1` 
            FOREIGN KEY (`inventory_id`) 
            REFERENCES `inventory` (`inventory_id`) 
            ON DELETE RESTRICT 
            ON UPDATE CASCADE');
    }
};
