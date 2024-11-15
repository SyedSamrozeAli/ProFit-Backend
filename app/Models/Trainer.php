<?php

namespace App\Models;

use App\Http\Requests\TrainerRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Trainer extends Model
{
    use HasFactory;
    protected $table = 'trainers';
    protected $primaryKey = 'trainer_id';

    static public function addTrainer(TrainerRequest $request)
    {
        $age = Carbon::parse($request->DOB)->age;

        // Adding profile image
        $profileImage = $request->file('trainer_profile_image');
        $imageName = time() . '.' . $profileImage->getClientOriginalExtension();
        $profileImage->move('images/trainer/', $imageName);

        // Store the trainer data in the database
        DB::statement(
            "INSERT INTO trainers (trainer_name,trainer_email,CNIC,gender,DOB,age,phone_number,trainer_address,experience,salary,availability,rating,trainer_profile_image)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?)
        
        ",
            [
                $request->trainer_name,
                $request->trainer_email,
                $request->CNIC,
                $request->gender,
                $request->DOB,
                $age,
                $request->phone_number,
                $request->trainer_address,
                $request->experience,
                $request->salary,
                1, // 1 means TRUE
                $request->rating,
                $imageName
            ]
        );
    }

    static public function findTrainer($filter, $value)
    {
        return DB::select("SELECT * FROM trainers WHERE " . $filter . "=?", [$value]);
    }


    static public function giveFilteredQuery(TrainerRequest $request, &$filterValues)
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

    static public function updateTrainer($updateQuery, $updateValues)
    {
        DB::update($updateQuery, $updateValues);

    }

    static public function addMember($trainerId, $memberId)
    {
        DB::insert("INSERT INTO trainers_have_members (trainer_id, member_id) VALUES (?,?)", [$trainerId, $memberId]);
    }

    static public function updateMember($trainerId, $memberId)
    {
        $updatedRow = DB::update("UPDATE trainers_have_members SET trainer_id=? WHERE member_id=?", [$trainerId, $memberId]);

        if ($updatedRow > 0)
            return true;
        else
            return false;
    }

    static public function deleteMember($memberId)
    {
        DB::delete("DELETE FROM trainers_have_members WHERE member_id=?", [$memberId]);
    }
}
