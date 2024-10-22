<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'trainer_id' => $this->trainer_id,
            'trainer_name' => $this->trainer_name,
            'trainer_email' => $this->trainer_email,
            'age' => $this->age,
            'gender' => $this->gender,
            'DOB' => $this->DOB,
            'salary' => $this->salary,
            'phone_number' => $this->phone_number,
            'trainer_address' => $this->trainer_address,
            'experience' => $this->experience,
            'rating' => $this->rating,
            'hire_date' => $this->hire_date,
            'status' => $this->availability,
            'CNIC' => $this->CNIC,

        ];
    }
}
