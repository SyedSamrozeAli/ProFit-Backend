<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropUnwantedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modifying columns in the equipment table
        DB::statement("
ALTER TABLE `equipments`
DROP COLUMN `mantainance_date`,
DROP COLUMN `warranty_period`,
DROP COLUMN `usage_duration`,
DROP COLUMN `purchase_date`,
ADD COLUMN `quantity` INT NULL AFTER `price`
");

        // Modifying columns in the inventory table
        DB::statement("
ALTER TABLE `inventory`
ADD COLUMN `mantainance_date` DATE NULL AFTER `total_price`,
ADD COLUMN `warranty_period` INT NULL AFTER `mantainance_date`,
ADD COLUMN `usage_duration` INT NULL AFTER `warranty_period`,
DROP COLUMN `stock_reorder_level`
");

        // Modifying columns in the inventory_payments table
        DB::statement("
ALTER TABLE `inventory_payments`
DROP COLUMN `balance_due_date`
");

        DB::statement(" ALTER TABLE trainers ADD COLUMN client_count INT NULL DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Re-adding columns back to the equipment table (in case of rollback)
        DB::statement("
ALTER TABLE `equipments`
ADD COLUMN `mantainance_date` DATE NULL,
ADD COLUMN `warranty_period` INT NULL,
ADD COLUMN `usage_duration` INT NULL,
ADD COLUMN `purchase_date` DATE NULL,
DROP COLUMN `quantity`
");

        // Removing the newly added columns from the inventory table (in case of rollback)
        DB::statement("
ALTER TABLE `inventory`
DROP COLUMN `mantainance_date`,
DROP COLUMN `warranty_period`,
DROP COLUMN `usage_duration`,
ADD COLUMN `stock_reorder_level` INT NULL
");

        // Re-adding the dropped column in the inventory_payments table
        DB::statement("
ALTER TABLE `inventory_payments`
ADD COLUMN `balance_due_date` DATE NULL
");
        DB::statement(" ALTER TABLE trainers DROP COLUMN client_count");


    }
}