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
        DB::insert("INSERT INTO expense_categories (expense_category_name) VALUES (?)", ['utilities']);
        DB::insert("INSERT INTO expense_categories (expense_category_name) VALUES (?)", ['rent']);
        DB::insert("INSERT INTO expense_categories (expense_category_name) VALUES (?)", ['mantainance and repair']);
        DB::insert("INSERT INTO expense_categories (expense_category_name) VALUES (?)", ['advertising']);
        DB::insert("INSERT INTO expense_categories (expense_category_name) VALUES (?)", ['taxes']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DELETE FROM expense_categories WHERE expense_category_name IN ('utilities', 'rent', 'mantainance and repair', 'advertising', 'taxes')");
    }
};
