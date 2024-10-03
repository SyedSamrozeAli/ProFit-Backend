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
        
            CREATE TABLE trainers_payments (
            
                trainer_payment_id INT PRIMARY KEY AUTO_INCREMENT,
                trainer_id INT,
                FOREIGN KEY (trainer_id) REFERENCES trainers (trainer_id) ON DELETE SET NULL ON UPDATE CASCADE,
                payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                salary DECIMAL(10,2),
                payment_amount DECIMAL(10, 2) ,
                paid_amount DECIMAL(10, 2),
                dues DECIMAL(10, 2) DEFAULT 0,
                balance DECIMAL(10, 2) DEFAULT 0,
                payment_status ENUM('pending','completed','failed'),
                payment_method ENUM('cash','online'),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            
            )
        
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS trainers_payments');
    }
};
