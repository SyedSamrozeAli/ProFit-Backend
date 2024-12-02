<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerAttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attendance_date' => $this->attendance_date,
            'trainer_id' => $this->trainer_id,
            'trainer_name' => $this->trainer_name,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'attendance_status' => $this->attendance_status,
        ];
        ;
    }
}
