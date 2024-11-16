<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attendance_id' => $this->member_attendance_id,
            'member_id' => $this->member_id,
            'attendance_date' => $this->attendance_date,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'attendance_status' => $this->attendance_status,
        ];
        ;
    }
}
