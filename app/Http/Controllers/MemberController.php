<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Trainer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    protected $pageNo = 1;
    protected $recordPerPage = 10;

    public function storeMember(MemberRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {


                $membership_duration = $request->membership_duration;

                // Adding the new member in Database
                Member::addNewMember($request);

                // Getting the newly added member's ID using CNIC
                $newMember = Member::getMemberID($request->CNIC);

                // Setting price according to membership duration and membership type
                if ($request->membership_type == 'Premium') {

                    if ($membership_duration == 3) {
                        $price = 30000;  // 30k for 3 months
                    } else if ($membership_duration == 6) {
                        $price = 52000; // 52k for 6 months
                    } else {
                        $price = 172000; // 175k for 12 months
                    }

                    // Adding the newly created member to the trainer's list of members
                    Trainer::addMember($request->trainer_id, $newMember[0]->member_id);


                } else if ($request->membership_type == 'Standard') {
                    if ($membership_duration == 3) {
                        $price = 15000;  // 15k for 3 months 
                    } else if ($membership_duration == 6) {
                        $price = 28000;  // 28k for 6 months
                    } else {
                        $price = 54000;  // 54k for 12 months
                    }
                }

                // Getting the membership ID based on the membership type provided in the request
                $membership = Membership::getMembershipID($request->membership_type);

                // Adding the member into the member_has_membership table
                Membership::addMemberIntoMembership($newMember[0]->member_id, $membership[0]->membership_id, $price, $membership_duration);

            });

            return successResponse("Member added successfully");

        } catch (\Exception $e) {
            return errorResponse($e->getMessage(), 500);
        }
    }

    public function getSpecificMember($memberId)
    {
        try {

            // Finding the member by ID
            $member = Member::findMember('member_id', $memberId);
            if (!empty($member)) {

                // Return a success response with the member data
                return successResponse("Member retrieved successfully", MemberResource::make($member[0]));

            } else {

                // Return an error response if the member is not found
                return errorResponse("Member not found", 404);
            }

        } catch (\Exception $e) {

            return errorResponse($e->getMessage());
        }

    }

    public function updatemember(MemberRequest $request, $memberId)
    {
        try {
            DB::transaction(function () use ($request, $memberId) {
                // Initialize the update query and bind parameters array
                $updateQuery = "UPDATE members SET ";
                $updateFields = [];
                $updateValues = [];

                // Check which fields are present in the request and build the query accordingly
                // It will concatinate the values if the request contains multiple fields
                if ($request->has('member_name')) {
                    $updateFields[] = "name = ?";
                    $updateValues[] = $request->member_name;
                }

                if ($request->has('member_email')) {
                    $updateFields[] = "member_email = ?";
                    $updateValues[] = $request->member_email;
                }

                if ($request->has('CNIC')) {
                    $updateFields[] = "CNIC = ?";
                    $updateValues[] = $request->CNIC;
                }


                if ($request->has('gender')) {
                    $updateFields[] = "gender = ?";
                    $updateValues[] = $request->gender;
                }

                if ($request->has('DOB')) {
                    $updateFields[] = "DOB = ?";
                    $updateValues[] = $request->DOB;

                    $age = Carbon::parse($request->DOB)->age;
                    $updateFields[] = "age = ?";
                    $updateValues[] = $age;
                }

                if ($request->has('phone_number')) {
                    $updateFields[] = "phone_number = ?";
                    $updateValues[] = $request->phone_number;
                }

                if ($request->has('address')) {
                    $updateFields[] = "member_address = ?";
                    $updateValues[] = $request->member_address;
                }

                if ($request->has('weight') && $request->has('height')) {
                    $updateFields[] = "weight = ?";
                    $updateValues[] = $request->weight;

                    $updateFields[] = "height = ?";
                    $updateValues[] = $request->height;

                    $BMI = $request->weight / (($request->height) * ($request->height));
                    $updateFields[] = "BMI =?";
                    $updateValues[] = $BMI;
                }

                if ($request->has('health_issues')) {
                    $updateFields[] = "health_issues = ?";
                    $updateValues[] = $request->health_issues;
                }

                if ($request->has('trainer_id')) {
                    Trainer::updateMember($request->trainer_id, $memberId);
                }

                if ($request->has('profile_image')) {
                    $updateFields[] = "profile_image = ?";
                    $updateValues[] = $request->profile_image;
                }

                if ($request->has('member_status')) {
                    $updateFields[] = "user_status = ?";
                    $updateValues[] = $request->member_status;
                }

                if ($request->has('addmission_date')) {
                    $updateFields[] = "addmission_date = ?";
                    $updateValues[] = $request->addmission_date;
                }

                if ($request->has('membership_duration') && $request->has('membership_type')) {
                    $membership_duration = $request->membership_duration;
                    $endDate = Carbon::parse($request->start_date)->addMonths($membership_duration);
                    // Getting the membership ID based on the membership type provided in the request
                    $membership = Membership::getMembershipID($request->membership_type);

                    // Getting the current membership of the member
                    $currentMembership = Membership::getMemberMembership($memberId);

                    if ($request->membership_type == 'Premium') {

                        if ($membership_duration == 3) {
                            $price = 30000;  // 30k for 3 months
                        } else if ($membership_duration == 6) {
                            $price = 52000; // 52k for 6 months
                        } else {
                            $price = 172000; // 172k for 12 months
                        }

                        // If the membership is changing from 'Standard' --> 'Premium' only then we will add
                        // the member in trainer's table.
                        if ($currentMembership[0]->membership_id == 1)
                            Trainer::addMember($request->trainer_id, $memberId);

                    } else if ($request->membership_type == 'Standard') {

                        if ($membership_duration == 3) {
                            $price = 15000;  // 15k for 3 months 
                        } else if ($membership_duration == 6) {
                            $price = 28000;  // 28k for 6 months
                        } else {
                            $price = 54000;  // 54k for 12 months
                        }

                        // Deleting the member from the trainers list
                        Trainer::deleteMember($memberId);
                    }

                    // Updating the membership in the database
                    Membership::updateMembership($memberId, $price, $membership_duration, $request->start_date, $endDate, $membership[0]->membership_id);

                }

                // If no fields were sent, return an error response
                if (empty($updateFields)) {
                    return errorResponse("No fields to update", 400);
                }

                // Add the member ID to the bind parameters array
                $updateValues[] = $memberId;

                // Finalize the query
                // 'implode' transforms the elements of an array in to a single string seperated by a delimiter in this case it is a comma
                $updateQuery .= implode(", ", $updateFields) . " WHERE member_id = ?";

                // Execute the update query
                Member::updatemember($updateQuery, $updateValues);

                // Find the updated member

            });

            $updatedMember = Member::findMember('member_id', $memberId);

            if (!empty($updatedMember)) {

                // Return a success response with the updated member data
                return successResponse("Member updated successfully", MemberResource::make($updatedMember[0]));

            } else {
                return errorResponse("Member not found", 404);
            }
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function deleteMember($memberId)
    {
        try {
            // Delete the member from the database
            $deletedRows = DB::delete("DELETE FROM members WHERE member_id = ?", [$memberId]);

            // Check if any row was actually deleted
            if ($deletedRows) {
                return successResponse("Member deleted successfully");
            } else {
                return errorResponse("Member not found", 404);
            }
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function getMembers(MemberRequest $request)
    {
        try {
            $filterValues = [];

            // Checking the request for any filters
            if (!$request->query()) {
                // No filters provided, so get all trainers from the database
                $DBquery = "SELECT * FROM members";

            } else {
                // Getting the filtered query
                $DBquery = Member::giveFilteredQuery($request, $filterValues);
            }

            if ($request->paginate) {

                // Pagination settings 
                $page = $request->query('page', $this->pageNo); // Default is page 1
                $limit = $request->query('recordPerPage', $this->recordPerPage); // Default is 10 records per page
                $offset = ($page - 1) * $limit;

                // Add limit and offset to the query
                $DBquery .= " LIMIT ? OFFSET ?";
                $filterValues[] = $limit;
                $filterValues[] = $offset;
            }

            // Extracting the data according to the filter

            $members = DB::select($DBquery, $filterValues);


            // Check if trainers are found
            if (!empty($members)) {
                return successResponse("Members retrieved successfully", MemberResource::collection($members), $request->paginate);
            }

            return errorResponse("No members found", 404);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }



}
