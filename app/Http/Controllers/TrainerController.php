<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerRequest;
use App\Http\Resources\TrainerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
    protected $pageNo = 1;
    protected $recordPerPage = 10;

    public function storeTrainer(TrainerRequest $request)
    {

        // Store the trainer data in the database
        DB::statement("
            
            INSERT INTO trainers (trainer_name,trainer_email,CNIC,age,gender,DOB,phone_number,trainer_address,experience,salary,hourly_rate,availability,rating)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)
        
        ",
            [
                $request->trainer_name,
                $request->trainer_email,
                $request->CNIC,
                $request->age,
                $request->gender,
                $request->DOB,
                $request->phone_number,
                $request->trainer_address,
                $request->experience,
                $request->salary,
                $request->hourly_rate,
                1, // 1 means TRUE
                0
            ]
        );

        // Finding the newly added trainer
        $newTrainer = DB::select("SELECT * FROM trainers WHERE trainer_email=?", [$request->trainer_email]);

        // Return a success response with the trainer data
        return successResponse("Trainer added successfully", TrainerResource::make($newTrainer[0]));

    }


    // Fetching a specific trainer through ID
    public function getSpecificTrainer($trainerId)
    {
        try {

            // Finding the trainer by ID
            $trainer = DB::select("SELECT * FROM trainers WHERE trainer_id=?", [$trainerId]);  // 'Select' statement return an array
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

            if ($request->has('age')) {
                $updateFields[] = "age = ?";
                $updateValues[] = $request->age;
            }

            if ($request->has('gender')) {
                $updateFields[] = "gender = ?";
                $updateValues[] = $request->gender;
            }

            if ($request->has('DOB')) {
                $updateFields[] = "DOB = ?";
                $updateValues[] = $request->DOB;
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

            if ($request->has('hourly_rate')) {
                $updateFields[] = "hourly_rate = ?";
                $updateValues[] = $request->hourly_rate;
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
            DB::update($updateQuery, $updateValues);

            // Find the updated trainer
            $updatedTrainer = DB::select("SELECT * FROM trainers WHERE trainer_id = ?", [$trainerId]);

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
                $DBquery = $this->giveFilteredQuery($request, $filterValues);
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


    protected function giveFilteredQuery(TrainerRequest $request, &$filterValues)
    {

        $DBquery = "SELECT * FROM trainers";

        // Dynamically building the query string for the required filters only
        if ($request->has('name') && !empty($request->query('name'))) {
            $filterFields[] = "trainer_name LIKE?";
            $filterValues[] = "%" . $request->name . "%";
        }

        if ($request->has('email') && !empty($request->query('email'))) {
            $filterFields[] = "trainer_email LIKE?";
            $filterValues[] = "%" . $request->email . "%";
        }

        if ($request->has('CNIC') && !empty($request->query('CNIC'))) {
            $filterFields[] = "CNIC LIKE?";
            $filterValues[] = "%" . $request->CNIC . "%";
        }

        if ($request->has('gender') && !empty($request->query('gender'))) {
            $filterFields[] = "gender= ?";
            $filterValues[] = $request->gender;
        }

        if ($request->has('maxAge') && !empty($request->query('maxAge'))) {
            $filterFields[] = "age <= ?";
            $filterValues[] = $request->maxAge;
        }

        if ($request->has('minAge') && !empty($request->query('minAge'))) {
            $filterFields[] = "age >= ?";
            $filterValues[] = $request->minAge;
        }

        if ($request->has('minSalary') && !empty($request->query('minSalary'))) {
            $filterFields[] = "salary >= ?";
            $filterValues[] = $request->minSalary;
        }

        if ($request->has('maxSalary') && !empty($request->query('maxSalary'))) {
            $filterFields[] = "salary <= ?";
            $filterValues[] = $request->maxSalary;
        }

        if ($request->has('availability') && !empty($request->query('availability'))) {
            $filterFields[] = "availability=?";
            if ($request->availability == 'active')
                $filterValues[] = 1;
            else if ($request->availability == 'inactive')
                $filterValues[] = 0;
        }

        if ($request->has('maxExperience') && !empty($request->query('maxExperience'))) {
            $filterFields[] = "experience <=?";
            $filterValues[] = $request->maxExperience;
        }
        if ($request->has('minExperience') && !empty($request->query('minExperience'))) {
            $filterFields[] = "experience >=?";
            $filterValues[] = $request->minExperience;
        }

        if ($request->has('startHireDate') && !empty($request->query('startHireDate'))) {
            $filterFields[] = "hire_date >=?";
            $filterValues[] = $request->startHireDate;
        }
        if ($request->has('endHireDate') && !empty($request->query('endHireDate'))) {
            $filterFields[] = "hire_date <=?";
            $filterValues[] = $request->endHireDate;
        }
        if ($request->has('minRating') && !empty($request->query('minRating'))) {
            $filterFields[] = "rating >=?";
            $filterValues[] = $request->minRating;
        }
        if ($request->has('maxRating') && !empty($request->query('maxRating'))) {
            $filterFields[] = "rating <=?";
            $filterValues[] = $request->maxRating;
        }

        $orderByQuery = "";
        $orderByQueryFields = [];

        // Dynamically building the order by query string for the required fields only
        if ($request->has('orderByName') && !empty($request->query('orderByName'))) {
            $orderByQueryFields[] = "name " . $request->orderByName;
        }

        if ($request->has('orderBySalary') && !empty($request->query('orderBySalary'))) {
            $orderByQueryFields[] = "salary " . $request->orderBySalary;
        }

        if ($request->has('orderByHireDate') && !empty($request->query('orderByHireDate'))) {
            $orderByQueryFields[] = "hire_date " . $request->orderByHireDate;
        }

        if ($request->has('orderByRating') && !empty($request->query('orderByRating'))) {
            $orderByQueryFields[] = "rating " . $request->orderByRating;
        }

        if (!empty($orderByQueryFields)) {
            $orderByQuery = "ORDER BY " . implode(',', $orderByQueryFields);
        }

        if (!empty($filterFields)) {
            // Concatenating the filtered query fields
            $DBquery = $DBquery . " WHERE " . implode(" AND ", $filterFields);
        }

        // Conactinating the order by query
        $DBquery .= " " . $orderByQuery;


        return $DBquery;

    }
}
