<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerRequest;
use App\Http\Resources\TrainerResource;
use App\Models\Trainer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class TrainerController extends Controller
{
    protected $pageNo = 1;
    protected $recordPerPage = 10;

    public function storeTrainer(TrainerRequest $request)
    {

        Trainer::addTrainer($request);

        // Finding the newly added trainer
        $newTrainer = Trainer::findTrainer('trainer_email', $request->trainer_email);

        // Return a success response with the trainer data
        return successResponse("Trainer added successfully", TrainerResource::make($newTrainer[0]));

    }


    // Fetching a specific trainer through ID
    public function getSpecificTrainer($trainerId)
    {
        try {

            // Finding the trainer by ID
            $trainer = Trainer::findTrainer('trainer_id', $trainerId);
            if (!empty($trainer)) {

                // Return a success response with the trainer data
                return successResponse("Trainer retrieved successfully", TrainerResource::make($trainer[0]));

            } else {

                // Return an error response if the trainer is not found
                return errorResponse("Trainer not found", 404);
            }

        } catch (\Exception $e) {

            return errorResponse($e->getMessage());
        }

    }


    public function updateTrainer(TrainerRequest $request, $trainerId)
    {
        try {
            // Initialize the update query and bind parameters array
            $updateQuery = "UPDATE trainers SET ";
            $updateFields = [];
            $updateValues = [];

            // Check which fields are present in the request and build the query accordingly
            // It will concatinate the values if the request contains multiple fields
            if ($request->has('trainer_name')) {
                $updateFields[] = "trainer_name = ?";
                $updateValues[] = $request->trainer_name;
            }

            if ($request->has('trainer_email')) {
                $updateFields[] = "trainer_email = ?";
                $updateValues[] = $request->trainer_email;
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

            if ($request->has('trainer_address')) {
                $updateFields[] = "trainer_address = ?";
                $updateValues[] = $request->trainer_address;
            }

            if ($request->has('experience')) {
                $updateFields[] = "experience = ?";
                $updateValues[] = $request->experience;
            }

            if ($request->has('salary')) {
                $updateFields[] = "salary = ?";
                $updateValues[] = $request->salary;
            }

            // If no fields were sent, return an error response
            if (empty($updateFields)) {
                return errorResponse("No fields to update", 400);
            }

            // Add the trainer ID to the bind parameters array
            $updateValues[] = $trainerId;

            // Finalize the query
            // 'implode' transforms the elements of an array in to a single string seperated by a delimiter in this case it is a comma
            $updateQuery .= implode(", ", $updateFields) . " WHERE trainer_id = ?";

            // Execute the update query
            Trainer::updateTrainer($updateQuery, $updateValues);

            // Find the updated trainer
            $updatedTrainer = Trainer::findTrainer('trainer_id', $trainerId);

            if (!empty($updatedTrainer)) {

                // Return a success response with the updated trainer data
                return successResponse("Trainer updated successfully", TrainerResource::make($updatedTrainer[0]));

            } else {
                return errorResponse("Trainer not found", 404);
            }

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


    public function deleteTrainer($trainerId)
    {
        try {
            // Delete the trainer from the database
            $deletedRows = DB::delete("DELETE FROM trainers WHERE trainer_id = ?", [$trainerId]);

            // Check if any row was actually deleted
            if ($deletedRows) {
                return successResponse("Trainer deleted successfully");
            } else {
                return errorResponse("Trainer not found", 404);
            }

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


    public function getTrainers(TrainerRequest $request)
    {
        try {
            $filterValues = [];

            // Checking the request for any filters
            if (!$request->query()) {
                // No filters provided, so get all trainers from the database
                $DBquery = "SELECT * FROM trainers";

            } else {
                // Getting the filtered query
                $DBquery = Trainer::giveFilteredQuery($request, $filterValues);
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

            $trainers = DB::select($DBquery, $filterValues);


            // Check if trainers are found
            if (!empty($trainers)) {
                return successResponse("Trainers retrieved successfully", TrainerResource::collection($trainers), $request->paginate);
            }

            return errorResponse("No trainers found", 404);

        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }


}
