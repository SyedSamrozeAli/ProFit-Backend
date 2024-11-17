<?php

namespace App\Http\Controllers;

use App\Models\TrainerAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TrainerAttendanceRequest;
use App\Http\Resources\TrainerAttendanceResource;

class TrainerAttendanceController extends Controller
{

    public function getAttendance(TrainerAttendanceRequest $request)
    {
        try {

            $date = $request->query('attendance_date');

            $attendances = TrainerAttendance::getAttendance($date);

            return successResponse("Attendance fetched succesfully", TrainerAttendanceResource::collection($attendances));

        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 501);
        }

    }

    public function addAttendance(TrainerAttendanceRequest $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->attendance as $attendance) {
                TrainerAttendance::updateOrCreate(
                    [
                        'trainer_id' => $attendance['trainer_id'],
                        'attendance_date' => $request->attendance_date,
                    ],
                    [
                        'check_in_time' => $attendance['check_in_time'],
                        'check_out_time' => $attendance['check_out_time'],
                        'attendance_status' => $attendance['attendance_status'],
                    ]
                );
            }

            DB::commit();
            return successResponse("Attendance recorded successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }
}
