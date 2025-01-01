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
        'due_date',
        'payment_amount',
    ];

    public $timestamps = false;

    static public function getPaymentData($month, $year, $expenseId = null)
    {
        $query = "  SELECT 
                        E.expense_id,
                        E.expense_date,
                        E.amount,
                        E.payment_amount,
                        E.due_date,
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

    public static function getOtherExpensesMonth($month, $year)
    {
        $query = " SELECT SUM(amount) AS total 
        FROM expense
        WHERE 1=1";

        $params = [];
        if ($month) {
            $query .= " AND MONTH(expense_date)=? ";
            $params[] = $month;
        }

        if ($year) {
            $query .= " AND YEAR(expense_date)=? ";
            $params[] = $year;
        }

        return DB::selectOne($query, $params)->total ?? 0;
    }

    public static function getMonthlyExpenses($monthOffset)
    {
        return DB::selectOne("
        SELECT 
            COALESCE(
                (SELECT SUM(paid_amount) FROM trainers_payments 
                 WHERE DATE_FORMAT(payment_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH), '%Y-%m')), 0
            ) 
            + 
            COALESCE(
                (SELECT SUM(amount) FROM expense 
                 WHERE DATE_FORMAT(expense_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH), '%Y-%m')), 0
            )
            + 
            COALESCE(
                (SELECT SUM(amount_paid) FROM inventory_payments 
                 WHERE DATE_FORMAT(payment_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH), '%Y-%m')), 0
            ) AS total_expenses
    ", [$monthOffset, $monthOffset, $monthOffset])->total_expenses ?? 0;
    }

    public static function calculateOtherExpenses($startDate, $endDate)
    {
        return DB::selectOne(
            "   SELECT SUM(amount) as total_expenses
                FROM expense
                WHERE expense_date BETWEEN ? AND ?",
            [$startDate, $endDate]
        )->total_expenses;
    }

}
