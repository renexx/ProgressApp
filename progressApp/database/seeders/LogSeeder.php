<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("log")->insert(array(
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 1,
                "created_at" => now(),
                "value" => 54
            ),
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 2,
                "created_at" => now(),
                "value" => 25
            ),
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 3,
                "created_at" => now(),
                "value" => 84
            ),
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 4,
                "created_at" => now(),
                "value" => 21
            ),
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 5,
                "created_at" => now(),
                "value" => 785
            ),
            array(
                "body_parameter_id" => NULL,
                "activity_id" => 6,
                "created_at" => now(),
                "value" => 12
            ),

            array(
                "body_parametery_id" => 1,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 87
            ),
            array(
                "body_parameter_id" => 2,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 20
            ),
            array(
                "body_parameter_id" => 3,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 25
            ),
            array(
                "body_parameter_id" => 4,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 90
            ),
            array(
                "body_parameter_id" => 5,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 69
            ),
            array(
                "body_parameter_id" => 6,
                "activity_id" => NULL,
                "created_at" => now(),
                "value" => 15
            ),
        ));
    }
}
