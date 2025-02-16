<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberAttendance extends Model
{
    use HasFactory;

    protected $table = 'members_attendance';
    protected $primaryKey = 'member_attendance_id';

    protected $fillable = [
        'member_id',
        'attendance_status',
        'attendance_date',
        'check_in_time',
        'check_out_time'
    ];

    public static function addAttendance($memberId, $date, $timeIn, $timeOut, $status)
    {
        $timeIn = date('H:i:s', strtotime($timeIn));
        $timeOut = date('H:i:s', strtotime($timeOut));

        DB::insert(
            "INSERT INTO members_attendance (member_id, attendance_date, check_in_time, check_out_time, attendance_status) VALUES (?, ?, ?, ?, ?)",
            [$memberId, $date, $timeIn, $timeOut, $status]
        );
    }

    /**
     * Get attendance based on date or member ID.
     */
    public static function getAttendance($date = null)
    {
        $query = "  SELECT 
                        M.member_id,
                        M.name AS member_name,
                        MA.attendance_date,
                        MA.check_in_time, 
                        MA.check_out_time,
                        MA.attendance_status
                    FROM 
                        members M
                    LEFT JOIN 
                        members_attendance MA
                    ON 
                        M.member_id = MA.member_id AND MA.attendance_date = ?";
        $params = [];

        if ($date) {
            $params[] = $date;
        } else {
            $params[] = NULL;
        }

        return DB::select($query, $params);
    }

    public static function updateAttendance($memberId, $date, $timeIn, $timeOut, $status)
    {
        $timeIn = date('H:i:s', strtotime($timeIn));
        $timeOut = date('H:i:s', strtotime($timeOut));

        DB::update(
            "UPDATE members_attendance SET check_in_time=?, check_out_time=?, attendance_status=? WHERE  member_id=? AND attendance_date=?",
            [$timeIn, $timeOut, $status, $memberId, $date]
        );
    }

    static public function getMemberAttendanceRate()
    {
        $attendanceRate = DB::select(
            "SELECT 
                (SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100 AS attendance_rate
            FROM members_attendance
            WHERE attendance_date BETWEEN DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') AND LAST_DAY(CURRENT_DATE())
            "
        )[0]->attendance_rate;

        return $attendanceRate ?? 0; // Default to 0 if no data exists
    }

    static public function getMemberAttendanceGrowthRate()
    {
        $currentMonth = DB::select(
            "SELECT 
                    SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) AS present_days,
                    COUNT(*) AS total_days
                FROM members_attendance
                WHERE attendance_date BETWEEN DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01') AND LAST_DAY(CURRENT_DATE())
                "
        );

        $previousMonth = DB::select(
            "SELECT 
                    SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) AS present_days,
                    COUNT(*) AS total_days
                FROM members_attendance
                WHERE attendance_date BETWEEN DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH), '%Y-%m-01') 
                AND LAST_DAY(DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))"
        );

        return [
            "currentMonth" => $currentMonth,
            "previousMonth" => $previousMonth,
        ];
    }
}
