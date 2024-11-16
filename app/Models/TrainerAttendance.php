<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrainerAttendance extends Model
{
    use HasFactory;

    protected $table = 'trainer_attendance';
    protected $primaryKey = 'trainer_attendance_id';

    protected $fillable = [
        'trainer_id',
        'attendance_status',
        'attendance_date',
        'check_in_time',
        'check_out_time'
    ];

    public static function addAttendance($trainerId, $date, $timeIn, $timeOut, $status)
    {
        $timeIn = date('H:i:s', strtotime($timeIn));
        $timeOut = date('H:i:s', strtotime($timeOut));

        DB::insert(
            "INSERT INTO trainers_attendance (trainer_id, attendance_date, check_in_time, check_out_time, attendance_status) VALUES (?, ?, ?, ?, ?)",
            [$trainerId, $date, $timeIn, $timeOut, $status]
        );
    }

    /**
     * Get attendance based on date or trainer ID.
     */
    public static function getAttendance($date = null)
    {
        $query = "SELECT * FROM trainers_attendance WHERE 1=1";
        $params = [];

        if ($date) {
            $query .= " AND attendance_date = ?";
            $params[] = $date;
        }

        return DB::select($query, $params);
    }

    public static function updateAttendance($trainerId, $date, $timeIn, $timeOut, $status)
    {
        $timeIn = date('H:i:s', strtotime($timeIn));
        $timeOut = date('H:i:s', strtotime($timeOut));

        DB::update(
            "UPDATE trainers_attendance SET check_in_time=?, check_out_time=?, attendance_status=? WHERE  trainer_id=? AND attendance_date=?",
            [$timeIn, $timeOut, $status, $trainerId, $date]
        );
    }
}
