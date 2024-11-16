<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\MemberAttendanceRequest;
use App\Http\Resources\MemberAttendanceResource;
use App\Models\MemberAttendance;
class MemberAttendanceController extends Controller
{
    public function addAttendance(MemberAttendanceRequest $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->attendance as $attend) {
                MemberAttendance::addAttendance(
                    $attend['member_id'],
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


    public function getAttendance(MemberAttendanceRequest $request)
    {
        try {

            $date = $request->query('date');

            $attendances = MemberAttendance::getAttendance($date);

            return successResponse("Attendance fetched succesfully", MemberAttendanceResource::collection($attendances));

        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage(), 501);
        }

    }

    public function updateAttendance(MemberAttendanceRequest $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->attendance as $attend) {
                MemberAttendance::updateAttendance(
                    $attend['member_id'],
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
