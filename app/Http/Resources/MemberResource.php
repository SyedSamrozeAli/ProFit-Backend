<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get the current HTTP method
        $method = $request->method();
        switch ($method) {
            case 'GET':
                // Extracting membership details for the member
                $membership = DB::select("SELECT * FROM member_has_membership ME JOIN memberships M ON ME.membership_id=M.membership_id WHERE member_id=?", [$this->member_id]);

                // Check if membership details were found
                $membershipData = !empty($membership) ? $membership[0] : null;

                $routeName = $request->route()->getName();

                // Attempt to get trainer's name, handling cases where the trainer might not be found
                $trainer = DB::select("SELECT trainer_name FROM trainers T JOIN trainers_have_members TM ON T.trainer_id=TM.trainer_id WHERE member_id=?", [$this->member_id]);
                $trainerName = !empty($trainer) ? $trainer[0]->trainer_name : null;

                if ($routeName == 'getSpecificMember') {
                    return [
                        'member_id' => $this->member_id,
                        'member_name' => $this->name,
                        'member_email' => $this->member_email,
                        'age' => $this->age,
                        'gender' => $this->gender,
                        'DOB' => $this->DOB,
                        'phone_number' => $this->phone_number,
                        'member_address' => $this->address,
                        'CNIC' => $this->CNIC,
                        'member_status' => $this->user_status,
                        'height' => $this->height,
                        'weight' => $this->weight,
                        'BMI' => $this->bmi,
                        'profile_image' => $this->profile_image,
                        'health_issues' => $this->health_issues,
                        'addmission_date' => $this->addmission_date,
                        'trainer_id' => $this->trainer_id,
                        'trainer_name' => $trainerName,
                        'membership_type' => $membershipData->membership_type ?? null,
                        'price' => $membershipData->price ?? null,
                        'start_date' => $membershipData->start_date ?? null,
                        'end_date' => $membershipData->end_date ?? null,
                    ];
                } else {
                    return [
                        'member_id' => $this->member_id,
                        'member_name' => $this->name,
                        'trainer_name' => $trainerName,
                        'membership_type' => $membershipData->membership_type ?? null,
                        'price' => $membershipData->price ?? null,
                        'start_date' => $membershipData->start_date ?? null,
                        'end_date' => $membershipData->end_date ?? null,
                        'member_status' => $this->user_status,
                    ];
                }

            default:
                return [];
        }
    }

}
