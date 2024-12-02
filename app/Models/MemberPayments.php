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


    static public function getPaymentData($month, $year, $memberId = null)
    {
        $query = "  SELECT 
                        M.member_id,
                        M.name AS member_name,
                        P.member_payment_id,
                        MM.membership_id,
                        MM.price,
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
                        member_has_membership MM
                    ON 
                        M.member_id=MM.member_id
                    LEFT JOIN 
                        members_payments P
                    ON 
                        M.member_id = P.member_id AND EXTRACT(MONTH FROM P.payment_date) = ? AND EXTRACT(YEAR FROM P.payment_date) = ? 
                    WHERE 
                        1=1";

        $params = [];

        if ($month && $year) {
            $params[] = $month;
            $params[] = $year;
        } else {
            $params[] = NULL;
        }

        if ($memberId) {
            $query .= " AND M.member_id =?";
            $params[] = $memberId;
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

    static public function getTotalRevenue($month, $year)
    {
        $query = "   SELECT SUM(paid_amount) as total_revenue 
                FROM members_payments WHERE 1=1";

        $params = [];

        if ($month && $year) {
            $query .= " AND MONTH(payment_date)=? AND YEAR(payment_date)=? ";
            $params[] = $month;
            $params[] = $year;
        }

        $totalRevenue = DB::select($query, $params)[0]->total_revenue;

        return $totalRevenue ?? 0; // Default to 0 if no payments exist
    }

    // static public function getTotalRevenue($month = 'current')
    // {
    //     $query = "SELECT COALESCE(SUM(paid_amount), 0) as total_revenue 
    //           FROM members_payments WHERE 1=1";

    //     if ($month === 'current') {
    //         $query .= " AND DATE_FORMAT(payment_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')";
    //     } elseif ($month === 'previous') {
    //         $query .= " AND DATE_FORMAT(payment_date, '%Y-%m') = DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH), '%Y-%m')";
    //     }

    //     $totalRevenue = DB::select($query)[0]->total_revenue;

    //     return $totalRevenue;
    // }


    static public function getRevenueGrowth()
    {
        $currentMonthCount = DB::select(
            "   SELECT sum(paid_amount) as total_revenue 
                FROM members_payments 
                WHERE YEAR(payment_date) = YEAR(CURRENT_DATE) 
                AND MONTH(payment_date) = MONTH(CURRENT_DATE)"
        )[0]->total_revenue;

        $lastMonthCount = DB::select(
            "   SELECT SUM(paid_amount) as total_revenue
                FROM members_payments 
                WHERE YEAR(payment_date) = YEAR(CURRENT_DATE) 
                AND MONTH(payment_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
    "
        )[0]->total_revenue;

        return [
            'current_month' => $currentMonthCount,
            'last_month' => $lastMonthCount
        ];
    }

    public static function calculateRevenue($startDate, $endDate)
    {
        return DB::selectOne(
            "   SELECT SUM(paid_amount) as total_revenue
                FROM members_payments
                WHERE payment_date BETWEEN ? AND ?"
            ,
            [$startDate, $endDate]
        )->total_revenue;
    }
}
