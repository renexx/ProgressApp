<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BodyParametersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("body_parameters")->insert(array(
            array(
                "user_id" => 1,
                "body_parameter_name" => "weight",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 2,
                "body_parameter_name" => "bmi",
                "unit" => "bmi",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 3,
                "body_parameter_name" => "fat_percentage",
                "unit" => "%",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 4,
                "body_parameter_name" => "weight",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 5,
                "body_parameter_name" => "weight",
                "unit" => "kg",
                "created_at" => now(),
                "updated_at" => now(),
            ),
            array(
                "user_id" => 6,
                "body_parameter_name" => "bmi",
                "unit" => "bmi",
                "created_at" => now(),
                "updated_at" => now(),
            ),
        ));
    }
}
