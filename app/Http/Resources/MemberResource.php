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
    {        // Get the current HTTP method
        $method = $request->method();
        switch ($method) {

            case 'GET':

                // Extracting membership details for the member
                $membership = DB::select("SELECT * FROM member_has_membership ME JOIN memberships M ON ME.membership_id=M.membership_id WHERE member_id=?", [$this->member_id]);

                $routeName = $request->route()->getName();

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
                        'trainer_name' => (DB::select("SELECT trainer_name FROM trainers WHERE trainer_id=?", [$this->trainer_id]))[0]->trainer_name,
                        'membership_type' => $membership[0]->membership_type,
                        'price' => $membership[0]->price,
                        'start_date' => $membership[0]->start_date,
                        'end_date' => $membership[0]->end_date,

                    ];

                    // when request through getAllMembers 
                } else

                    return [
                        'member_id' => $this->member_id,
                        'member_name' => $this->name,
                        'trainer_name' => (DB::select("SELECT trainer_name FROM trainers WHERE trainer_id=?", [$this->trainer_id]))[0]->trainer_name,
                        'membership_type' => $membership[0]->membership_type,
                        'price' => $membership[0]->price,
                        'start_date' => $membership[0]->start_date,
                        'end_date' => $membership[0]->end_date,
                        'member_status' => $this->user_status,
                    ];

            default:
                return [];
        }
    }
}
