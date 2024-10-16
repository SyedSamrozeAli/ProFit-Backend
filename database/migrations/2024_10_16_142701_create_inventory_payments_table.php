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
        DB::statement(

            " CREATE TABLE inventory_payments (
                inventory_payment_id INT AUTO_INCREMENT PRIMARY KEY,
                inventory_id INT NOT NULL,
                FOREIGN KEY (inventory_id) REFERENCES inventory(inventory_id),
                payment_date DATE NOT NULL,
                amount_paid FLOAT NOT NULL,
                payment_method ENUM('cash','online'),
                status ENUM('paid', 'pending', 'failed') DEFAULT 'pending',
                due_amount FLOAT DEFAULT 0,
                balance_due_date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL
            )
            "
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_payments');
    }
};
