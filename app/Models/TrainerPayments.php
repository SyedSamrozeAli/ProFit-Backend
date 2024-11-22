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


    static public function getPaymentData($month, $year)
    {
        $query = "  SELECT 
                        M.trainer_id,
                        M.trainer_name,
                        P.trainer_payment_id,
                        P.payment_date,
                        P.salary,
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
                        M.trainer_id = P.trainer_id AND EXTRACT(MONTH FROM P.payment_date) = ? AND EXTRACT(YEAR FROM P.payment_date) = ? ";

        $params = [];

        if ($month && $year) {
            $params[] = $month;
            $params[] = $year;
        } else {
            $params[] = NULL;
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
}
