<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainerAttendance extends Model
{
    use HasFactory;

    protected $table = 'trainers_attendance';
    protected $primaryKey = 'trainer_attendance_id';

    protected $fillable = [
        'trainer_id',
        'attendance_status',
        'attendance_date',
        'check_in_time',
        'check_out_time'
    ];

    // public static function addAttendance($trainerId, $date, $timeIn, $timeOut, $status)
    // {
    //     $timeIn = date('H:i:s', strtotime($timeIn));
    //     $timeOut = date('H:i:s', strtotime($timeOut));

    //     DB::insert(
    //         "INSERT INTO trainers_attendance (trainer_id, attendance_date, check_in_time, check_out_time, attendance_status) VALUES (?, ?, ?, ?, ?)",
    //         [$trainerId, $date, $timeIn, $timeOut, $status]
    //     );
    // }

    public static function getAttendance($date = NULL)
    {
        $query = "  SELECT 
                        M.trainer_id,
                        M.trainer_name,
                        MA.attendance_date,
                        MA.check_in_time, 
                        MA.check_out_time,
                        MA.attendance_status 
                    FROM trainers M 
                    LEFT JOIN trainers_attendance MA 
                    ON M.trainer_id = MA.trainer_id AND MA.attendance_date = ?";
        $params = [];

        if ($date) {
            $params[] = $date;
        } else {
            $params[] = NULL;
        }

        return DB::select($query, $params);
    }

    // public static function updateAttendance($trainerId, $date, $timeIn, $timeOut, $status)
    // {
    //     $timeIn = date('H:i:s', strtotime($timeIn));
    //     $timeOut = date('H:i:s', strtotime($timeOut));

    //     DB::update(
    //         "UPDATE trainers_attendance SET check_in_time=?, check_out_time=?, attendance_status=? WHERE  trainer_id=? AND attendance_date=?",
    //         [$timeIn, $timeOut, $status, $trainerId, $date]
    //     );
    // }
}
