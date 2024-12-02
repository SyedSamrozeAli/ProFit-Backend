<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $table = 'expense_categories';
    protected $primaryKey = 'expense_category_id';

    static public function getCategoryId($categoryName)
    {
        return DB::select("SELECT expense_category_id FROM expense_categories WHERE expense_category_name=?", [$categoryName])[0]->expense_category_id;
    }
}
