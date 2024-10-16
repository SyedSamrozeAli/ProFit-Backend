<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function storeMember(MemberRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {

                // Calculating the BMI 
                $BMI = $request->weight / (($request->height) * ($request->height));

                $membership_duration = $request->membership_duration;

                // Inserting member data into members table
                DB::insert("
                INSERT INTO members 
                (name, member_email, phone_number, address, age, CNIC, DOB, trainer_id, height, weight, bmi, membership_type, profile_image, health_issues, user_status, addmission_date, membership_start_date, membership_end_date)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ", [
                    $request->name,
                    $request->member_email,
                    $request->phone_number,
                    $request->address,
                    $request->age,
                    $request->CNIC,
                    $request->DOB,
                    $request->trainer_id,
                    $request->height,
                    $request->weight,
                    $BMI,
                    $request->membership_type,
                    $request->profile_image,
                    $request->health_issues,
                    'active',
                    $request->addmission_date,
                    now(),
                    now()->addDays($membership_duration * 30)
                ]);

                // Getting the newly added member's ID using CNIC
                $newMember = DB::select("SELECT member_id FROM members WHERE CNIC=?", [$request->CNIC]);

                // Setting price according to membership duration and membership type
                if ($request->membership_type == 'premium') {

                    if ($membership_duration == 3) {
                        $price = 52000;  // 52k for 3 months
                    } else if ($membership_duration == 6) {
                        $price = 100000; // 100k for 6 months
                    } else {
                        $price = 200000; // 200k for 12 months
                    }

                    // Adding the newly created member to the trainer's list of members
                    DB::insert("INSERT INTO trainers_have_members (trainer_id, member_id) VALUES (?,?)", [$request->trainer_id, $newMember[0]->member_id]);


                } else {
                    if ($membership_duration == 3) {
                        $price = 15000;  // 15k for 3 months 
                    } else if ($membership_duration == 6) {
                        $price = 28000;  // 28k for 6 months
                    } else {
                        $price = 54000;  // 54k for 12 months
                    }
                }

                // Adding new memberships for the newly added member
                DB::insert("
                INSERT INTO membership (membership_type, member_id, price, duration, start_date, end_date, status)
                VALUES(?,?,?,?,?,?,?)
            ", [
                    $request->membership_type,
                    $newMember[0]->member_id,
                    $price,
                    (string) $membership_duration,
                    now(),
                    now()->addDays($membership_duration * 30),
                    'active'
                ]);

                // Getting the newly added membership's ID
                $membership = DB::select("SELECT membership_id FROM membership WHERE member_id=?", [$newMember[0]->member_id]);

                // Updating the membership_id in members for the newly created member
                DB::update("UPDATE members SET membership_id=? WHERE member_id=?", [$membership[0]->membership_id, $newMember[0]->member_id]);


            });

            return successResponse("Member added successfully");

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

}
