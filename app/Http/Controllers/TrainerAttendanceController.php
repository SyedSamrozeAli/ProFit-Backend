<?php

namespace App\Http\Controllers;

use App\Models\TrainerAttendance;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TrainerAttendanceRequest;
use App\Http\Resources\TrainerAttendanceResource;

class TrainerAttendanceController extends Controller
{
    public function addAttendance(TrainerAttendanceRequest $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->attendance as $attend) {
                TrainerAttendance::addAttendance(
                    $attend['trainer_id'],
                    $request->attendance_date,
                    $attend['check_in_time'],
                    $attend['check_out_time'],
                    $attend['attendance_status']
                );
            }

            DB::commit();
            return successResponse("Attendance recorded successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }


    public function getAttendance(TrainerAttendanceRequest $request)
    {
        try {

            $date = $request->query('date');

            $attendances = TrainerAttendance::getAttendance($date);

            return successResponse("Attendance fetched succesfully", TrainerAttendanceResource::collection($attendances));

        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 501);
        }

    }

    public function updateAttendance(TrainerAttendanceRequest $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->attendance as $attend) {
                TrainerAttendance::updateAttendance(
                    $attend['trainer_id'],
                    $request->attendance_date,
                    $attend['check_in_time'],
                    $attend['check_out_time'],
                    $attend['attendance_status']
                );
            }

            DB::commit();
            return successResponse("Attendance updated successfully.", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 500);
        }
    }
}
