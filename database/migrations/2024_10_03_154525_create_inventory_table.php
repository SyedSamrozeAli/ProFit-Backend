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
        
            CREATE TABLE inventory (
            
                inventory_id INTEGER PRIMARY KEY AUTO_INCREMENT,
                equipment_id INT NOT NULL,
                FOREIGN KEY (equipment_id) REFERENCES equipments (equipment_id) ON DELETE CASCADE ON UPDATE CASCADE,
                cost_per_unit FLOAT,
                quantity INT,
                total_price FLOAT,
                stock_reorder_level INT,
                purchase_date DATE,
                supplier_name VARCHAR(55),
                dues FLOAT,
                balance FLOAT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP
            
            ) 
        
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS inventory");
    }
};
