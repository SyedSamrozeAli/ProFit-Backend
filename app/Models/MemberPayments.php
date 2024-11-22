<?php

namespace App\Models;

use App\Http\Requests\MemberPaymentsRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberPayments extends Model
{
    use HasFactory;

    protected $table = 'members_payments';

    protected $primaryKey = 'member_payment_id';

    protected $fillable =
        [
            'member_id',
            'payment_date',
            'membership_id',
            'payment_amount',
            'payment_status',
            'paid_amount',
            'dues',
            'balance',
            'payment_method',
        ];

    // static public function addPayment(MemberPaymentsRequest $req, $dues, $balance, $payment_status)
    // {

    //     $membership = Membership::getMembershipID($req->membership_type);
    //     DB::insert("INSERT INTO members_payments (member_id,membership_id,payment_date,payment_amount,payment_status,paid_amount,dues,balance,payment_method)
    //                 VALUES (?,?,?,?,?,?,?,?,?)",

    //         [
    //             $req->member_id,
    //             $membership[0]->membership_id,
    //             $req->payment_date,
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
                        M.member_id,
                        M.name AS member_name,
                        P.member_payment_id,
                        P.membership_id,
                        P.payment_date,
                        P.payment_amount,
                        P.payment_status,
                        P.paid_amount,
                        P.payment_method,
                        P.dues,
                        P.balance,
                        P.payment_reciept
                    FROM 
                        members M
                    LEFT JOIN 
                        members_payments P
                    ON 
                        M.member_id = P.member_id AND EXTRACT(MONTH FROM P.payment_date) = ? AND EXTRACT(YEAR FROM P.payment_date) = ? ";

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
        return DB::delete("DELETE FROM members_payments WHERE member_payment_id=? ", [$paymentId]);
    }
}
