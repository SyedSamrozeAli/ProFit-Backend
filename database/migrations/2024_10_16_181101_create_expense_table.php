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
            " CREATE TABLE expense (
                expense_id INT PRIMARY KEY AUTO_INCREMENT,
                expense_category INT NOT NULL,
                CONSTRAINT expense_category_fk FOREIGN KEY (expense_category) REFERENCES expense_categories(expense_category_id) ON DELETE CASCADE,
                amount FLOAT,
                expense_date DATE,
                expense_status ENUM('pending','completed','failed') DEFAULT 'pending',
                payment_method ENUM('cash','online') 
            
            )"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
