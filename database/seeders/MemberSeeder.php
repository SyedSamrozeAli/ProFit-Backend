<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            DB::beginTransaction();

            try {
                // Step 1: Insert member data
                $memberId = DB::table('members')->insertGetId([
                    'name' => $faker->name,
                    'member_email' => $faker->unique()->email,
                    'phone_number' => $faker->unique()->numerify('###########'),
                    'address' => $faker->address,
                    'age' => $faker->numberBetween(18, 65),
                    'CNIC' => $faker->unique()->numerify('#############'),
                    'gender' => $faker->randomElement(['male', 'female', 'others']),
                    'DOB' => $faker->date(),
                    'height' => $faker->numberBetween(3, 10),
                    'weight' => $faker->numberBetween(25, 200),
                    'bmi' => $faker->randomFloat(2, 18.5, 30),
                    'profile_image' => $faker->imageUrl(100, 100, 'people'),
                    'health_issues' => $faker->sentence,
                    'user_status' => 'Active',
                ]);

                // Step 2: Choose membership type and retrieve membership_id
                $membershipType = $faker->randomElement(['Standard', 'Premium']);
                $membership = DB::table('memberships')
                    ->where('membership_type', $membershipType)
                    ->first();

                $membership_duration = $faker->randomElement(['3', '6', '12']);
                $price = 0;
                if ($membershipType == 'Premium') {

                    if ($membership_duration == 3) {
                        $price = 30000;  // 30k for 3 months
                    } else if ($membership_duration == 6) {
                        $price = 52000; // 52k for 6 months
                    } else {
                        $price = 172000; // 172k for 12 months
                    }



                } else if ($membershipType == 'Standard') {

                    if ($membership_duration == 3) {
                        $price = 15000;  // 15k for 3 months 
                    } else if ($membership_duration == 6) {
                        $price = 28000;  // 28k for 6 months
                    } else {
                        $price = 54000;  // 54k for 12 months
                    }


                }

                // Step 3: Insert membership data
                DB::table('member_has_membership')->insert([
                    'member_id' => $memberId,
                    'membership_id' => $membership->membership_id,
                    'price' => $price,
                    'duration' => $membership_duration,
                    'start_date' => $faker->dateTimeThisYear(),
                    'end_date' => $faker->dateTimeThisYear('+1 year'),
                    'status' => 'active'
                ]);

                // Step 4: If "Premium", assign a random trainer
                if ($membershipType === 'Premium') {
                    $trainer = DB::table('trainers')->inRandomOrder()->first();

                    DB::table('trainers_have_members')->insert([
                        'trainer_id' => $trainer->trainer_id,
                        'member_id' => $memberId,
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                echo "Failed to create member with error: " . $e->getMessage();
            }
        }
    }
}
