<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainerRequest;
use App\Http\Resources\TrainerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainerController extends Controller
{
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
}
