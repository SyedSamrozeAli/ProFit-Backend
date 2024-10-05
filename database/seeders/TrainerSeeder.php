<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TrainerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Faker instance
        $faker = Faker::create();

        // Loop to insert 50 trainers
        for ($i = 1; $i <= 50; $i++) {
            DB::table('trainers')->insert([
                'trainer_name' => $faker->name,
                'trainer_email' => $faker->unique()->safeEmail,
                'CNIC' => $faker->unique()->numerify('###########'), // 13 digits CNIC
                'age' => $faker->numberBetween(18, 50),
                'gender' => $faker->randomElement(['male', 'female']),
                'DOB' => $faker->date('Y-m-d', '2010-12-31'), // Birthdate before 2010
                'phone_number' => $faker->numerify('03#########'), // Random phone number in the Pakistani format
                'trainer_profile_image' => null, // You can adjust this if needed
                'trainer_address' => $faker->address,
                'experience' => $faker->numberBetween(0, 10),
                'salary' => $faker->randomFloat(2, 30000, 50000), // Salary between 30k and 50k
                'hourly_rate' => $faker->randomFloat(2, 500, 1500), // Hourly rate between 500 and 1500
                'availability' => $faker->boolean,
                'hire_date' => $faker->dateTimeThisDecade(), // Random hire date within the past 10 years
                'rating' => $faker->randomFloat(1, 1, 5), // Rating between 1.0 and 5.0
            ]);
        }
    }
}
