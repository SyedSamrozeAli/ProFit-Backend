<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OtherExpensePayments extends Model
{
    use HasFactory;

    protected $table = 'expense';
    protected $primaryKey = 'expense_id';

    protected $fillable = [
        'expense_category',
        'amount',
        'expense_date',
        'expense_status',
        'payment_method',
        'payment_reciept',
    ];

    public $timestamps = false;

    static public function getPaymentData($month, $year, $expenseId = null)
    {
        $query = "  SELECT 
                        E.expense_id,
                        E.expense_date,
                        E.amount,
                        E.expense_status,
                        E.payment_method,
                        E.payment_reciept,
                        EC.expense_category_name
                    FROM 
                        expense E
                    JOIN 
                        expense_categories EC
                    ON 
                        E.expense_category = EC.expense_category_id
                    WHERE 
                        1=1 ";
        $params = [];

        if ($month && $year) {
            $query .= "AND EXTRACT(MONTH FROM E.expense_date) = ? AND EXTRACT(YEAR FROM E.expense_date) = ?";
            $params[] = $month;
            $params[] = $year;
        }

        if ($expenseId) {
            $query .= " AND E.expense_id =?";
            $params[] = $expenseId;
        }

        return DB::select($query, $params);


    }

    static public function deletePayment($paymentId)
    {
        return DB::delete("DELETE FROM expense WHERE expense_id=? ", [$paymentId]);
    }
}
