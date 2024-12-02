<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FinanceReportGenerationModel extends Model
{
    use HasFactory;

    static public function getPaymentHistoryDate($startDate, $endDate)
    {

        $query =
            "SELECT 
                'member' AS TYPE,
                member_payment_id AS payment_id,
                COALESCE(payment_amount, 0) AS payment_amount,
                COALESCE(paid_amount, 0) AS paid_amount,
                COALESCE(dues, 0) AS dues,
                COALESCE(balance, 0) AS balance,
                payment_date
               
            FROM 
                members_payments
            WHERE
                payment_date >= ? AND payment_date <= ?

            UNION

            SELECT 
            'trainer' AS TYPE,
                trainer_payment_id AS payment_id, 
                COALESCE(payment_amount, 0) AS payment_amount,
                COALESCE(paid_amount, 0) AS paid_amount,
                COALESCE(dues, 0) AS dues,
                COALESCE(balance, 0) AS balance,
                payment_date
               
            FROM 
                trainers_payments
            WHERE 
                payment_date >= ? AND payment_date <= ?

            UNION

            SELECT 
            'inventory' AS TYPE, 
                inventory_payment_id AS payment_id, 
                COALESCE(payment_amount, 0) AS payment_amount,
                COALESCE(amount_paid, 0) AS paid_amount,
                COALESCE(due_amount, 0) AS dues,
                COALESCE(balance, 0) AS balance,
                payment_date
            
            FROM 
                inventory_payments
            WHERE 
                payment_date >= ? AND payment_date <= ?

            UNION

            SELECT 
                'expense' AS TYPE, 
                expense_id AS payment_id, 
                COALESCE(payment_amount, 0) AS payment_amount,
                COALESCE(amount, 0) AS paid_amount,
                0 AS dues, 
                0 AS balance,  
                expense_date AS payment_date
                
            FROM 
                expense
            WHERE 
                expense_date >= ? AND expense_date <= ?

            ORDER BY payment_date;
";

        $params = [$startDate, $endDate, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate];

        return DB::select($query, $params);
    }
}
