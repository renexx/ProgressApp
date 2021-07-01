<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("activities")->insert(array(
            array(
                "user_id" => 1,
                "activity_name" => "squat",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 2,
                "activity_name" => "benchpress",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 3,
                "activity_name" => "deadlift",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 4,
                "activity_name" => "pull ups",
                "unit" => NULL,
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 5,
                "activity_name" => "push ups",
                "unit" => NULL,
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 6,
                "activity_name" => "running",
                "unit" => "km",
                "created_at" => now(),
                "updated_at" => now(),
            ),
        ));
    }
}
