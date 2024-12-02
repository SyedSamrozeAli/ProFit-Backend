<?php

namespace App\Models;

use App\Http\Requests\TrainerPaymentsRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainerPayments extends Model
{
    use HasFactory;

    protected $table = 'trainers_payments';

    protected $primaryKey = 'trainer_payment_id';

    protected $fillable =
        [
            'trainer_id',
            'payment_date',
            'salary',
            'payment_amount',
            'payment_status',
            'paid_amount',
            'dues',
            'balance',
            'payment_method',
        ];
    // static public function addPayment(TrainerPaymentsRequest $req, $dues, $balance, $payment_status)
    // {


    //     DB::insert("INSERT INTO trainers_payments (trainer_id,payment_date,salary,payment_amount,payment_status,paid_amount,dues,balance,payment_method)
    //                 VALUES (?,?,?,?,?,?,?,?,?)",

    //         [
    //             $req->trainer_id,
    //             $req->payment_date,
    //             $req->salary,
    //             $req->payment_amount,
    //             $payment_status,
    //             $req->paid_amount,
    //             $dues,
    //             $balance,
    //             $req->payment_method

    //         ]
    //     );
    // }


    static public function getPaymentData($month, $year, $trainerId = null)
    {
        $query = "  SELECT 
                        M.trainer_id,
                        M.trainer_name,
                        M.salary,
                        P.trainer_payment_id,
                        P.payment_date,
                        P.payment_amount,
                        P.payment_status,
                        P.paid_amount,
                        P.payment_method,
                        P.dues,
                        P.balance,
                        P.payment_reciept
                    FROM 
                        trainers M
                    LEFT JOIN 
                        trainers_payments P
                    ON 
                        M.trainer_id = P.trainer_id AND EXTRACT(MONTH FROM P.payment_date) = ? AND EXTRACT(YEAR FROM P.payment_date) = ? 
                    WHERE 
                        1=1";

        $params = [];

        if ($month && $year) {
            $params[] = $month;
            $params[] = $year;
        } else {
            $params[] = NULL;
        }

        if ($trainerId) {
            $query .= " AND M.trainer_id =?";
            $params[] = $trainerId;
        }
        return DB::select($query, $params);

    }

    // static public function updatePayment($updateQuery, $updateValues)
    // {
    //     return DB::update($updateQuery, $updateValues);
    // }


    static public function deletePayment($paymentId)
    {
        return DB::delete("DELETE FROM trainers_payments WHERE trainer_payment_id=? ", [$paymentId]);
    }

    public static function getTrainerSalariesMonth($month, $year)
    {
        $query = " SELECT SUM(paid_amount) AS total 
        FROM trainers_payments 
        WHERE 1=1";

        $params = [];
        if ($month && $year) {
            $query .= " AND MONTH(payment_date)=? AND YEAR(payment_date)=? ";
            $params[] = $month;
            $params[] = $year;
        }

        return DB::selectOne($query, $params)->total ?? 0;

    }

    public static function calculateTrainerPayments($startDate, $endDate)
    {
        return DB::selectOne(
            "   SELECT SUM(paid_amount) as total_trainer_payments
                FROM trainers_payments
                WHERE payment_date BETWEEN ? AND ?"
            ,
            [$startDate, $endDate]
        )->total_trainer_payments;
    }
}
