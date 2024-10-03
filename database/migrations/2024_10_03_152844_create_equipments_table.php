<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        
            CREATE TABLE equipments (
            
                equipment_id INTEGER PRIMARY KEY AUTO_INCREMENT,
                equipment_name VARCHAR(55) NOT NULL,
                category ENUM('Cardio Equipment','Resistance Machine','Free Weights','Accessories') NOT NULL,
                price FLOAT NOT NULL,
                mantainance_date DATE,
                equipment_status ENUM ('In Stock','Out of Stock'),
                desctiption TEXT,
                warranty_period INT,  -- in months
                usage_duration INT,   -- in months
                purchase_date DATE
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS equipments");
    }
};
