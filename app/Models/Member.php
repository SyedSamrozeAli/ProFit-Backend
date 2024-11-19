<?php

namespace App\Models;

use App\Http\Requests\MemberRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $primaryKey = 'member_id';

    protected $fillable = [
        'name',
        'member_email',
        'phone_number',
        'address',
        'age',
        'CNIC',
        'DOB',
        'trainer_id',
        'height',
        'weight',
        'bmi',
        'membership_type',
        'profile_image',
        'health_issues',
        'user_status',
        'addmission_date',
        'membership_start_date',
        'membership_end_date'

    ];

    protected $casts = [

        'addmission_date' => 'datetime:Y-m-d H:m:s',
        'membership_start_date' => 'datetime:Y-m-d H:m:s',
        'membership_end_date' => 'datetime:Y-m-d H:m:s',
    ];

    static public function findMember($filter, $value)
    {
        return DB::select("SELECT * FROM members WHERE " . $filter . "=?", [$value]);
    }

    static public function addNewMember(MemberRequest $request)
    {
        // Calculating the BMI 
        $BMI = $request->weight / (($request->height) * ($request->height));
        $age = Carbon::parse($request->DOB)->age;

        // Adding profile image
        $profileImage = $request->file('profile_image');
        $imageName = time() . '.' . $profileImage->getClientOriginalExtension();
        $profileImage->move('images/member/', $imageName);

        // Inserting member data into members table
        DB::insert(
            "INSERT INTO members 
                    (name, member_email, phone_number, address, CNIC, DOB,age, height, weight, bmi, profile_image, health_issues, user_status, addmission_date)
                    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
            ,
            [
                $request->name,
                $request->member_email,
                $request->phone_number,
                $request->address,
                $request->CNIC,
                $request->DOB,
                $age,
                $request->height,
                $request->weight,
                $BMI,
                $imageName,
                $request->health_issues,
                'active',
                $request->addmission_date
            ]
        );
    }

    static public function getMemberId($CNIC)
    {
        return DB::select("SELECT member_id FROM members WHERE CNIC=?", [$CNIC]);
    }

    static public function updateMember($updateQuery, $updateValues)
    {
        DB::update($updateQuery, $updateValues);

    }

    static public function giveFilteredQuery(MemberRequest $request, &$filterValues)
    {

        $DBquery = "SELECT * FROM members";

        // Dynamically building the query string for the required filters only
        if ($request->has('name') && !empty($request->query('name'))) {
            $filterFields[] = "name LIKE?";
            $filterValues[] = "%" . $request->name . "%";
        }

        if ($request->has('email') && !empty($request->query('email'))) {
            $filterFields[] = "member_email LIKE?";
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

        if ($request->has('member_status') && !empty($request->query('member_status'))) {
            $filterFields[] = "user_status=?";
            if ($request->availability == 'active')
                $filterValues[] = 1;
            else if ($request->availability == 'inactive')
                $filterValues[] = 0;
        }

        if ($request->has('startaddmissionDate') && !empty($request->query('startaddmissionDate'))) {
            $filterFields[] = "hire_date >=?";
            $filterValues[] = $request->startaddmissionDate;
        }
        if ($request->has('endaddmissionDate') && !empty($request->query('endaddmissionDate'))) {
            $filterFields[] = "hire_date <=?";
            $filterValues[] = $request->endaddmissionDate;
        }


        $orderByQuery = "";
        $orderByQueryFields = [];

        // Dynamically building the order by query string for the required fields only
        if ($request->has('orderByName') && !empty($request->query('orderByName'))) {
            $orderByQueryFields[] = "name " . $request->orderByName;
        }

        if ($request->has('orderByaddmissionDate') && !empty($request->query('orderByaddmissionDate'))) {
            $orderByQueryFields[] = "hire_date " . $request->orderByaddmissionDate;
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
