<?php

namespace App\Models;

use App\Http\Requests\inventoryPaymentsRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryPayments extends Model
{
    use HasFactory;

    protected $table = 'inventory_payments';

    protected $primaryKey = 'inventory_payment_id';

    protected $fillable =
        [
            'inventory_id',
            'payment_date',
            'payment_amount',
            'payment_status',
            'amount_paid',
            'due_amount',
            'balance',
            'payment_method',
        ];


    static public function getPaymentData($month, $year, $inventoryId = null)
    {
        $query = "  SELECT 
                        I.inventory_id,
                        E.equipment_name,
                        P.inventory_payment_id,
                        I.total_price,
                        P.payment_date,
                        P.payment_amount,
                        P.payment_status,
                        P.amount_paid,
                        P.payment_method,
                        P.due_amount,
                        P.balance,
                        P.payment_reciept
                    FROM 
                        inventory I
                    JOIN 
                        equipments E
                    ON 
                        I.equipment_id = E.equipment_id
                    LEFT JOIN 
                        inventory_payments P
                    ON 
                        I.inventory_id = P.inventory_id 
                    WHERE 
                        1=1 AND EXTRACT(MONTH FROM I.purchase_date) = ? AND EXTRACT(YEAR FROM I.purchase_date) = ?";
        $params = [];

        if ($month && $year) {
            $params[] = $month;
            $params[] = $year;
        } else {
            $params[] = NULL;
        }
        if ($inventoryId) {
            $query .= " AND I.inventory_id =?";
            $params[] = $inventoryId;
        }
        return DB::select($query, $params);


    }

    static public function deletePayment($paymentId)
    {
        return DB::delete("DELETE FROM inventory_payments WHERE inventory_payment_id=? ", [$paymentId]);
    }

    public static function getInventoryPaymentsMonth($month, $year)
    {
        $query = " SELECT SUM(amount_paid) AS total 
        FROM inventory_payments 
        WHERE 1=1";

        $params = [];
        if ($month && $year) {
            $query .= " AND MONTH(payment_date)=? AND YEAR(payment_date)=? ";
            $params[] = $month;
            $params[] = $year;
        }

        return DB::selectOne($query, $params)->total ?? 0;
    }

    public static function calculateInventoryPayments($startDate, $endDate)
    {
        return DB::selectOne(
            "SELECT SUM(amount_paid) as total_inventory_payments
            FROM inventory_payments
            WHERE payment_date BETWEEN ? AND ?",
            [$startDate, $endDate]
        )->total_inventory_payments;
    }
}
