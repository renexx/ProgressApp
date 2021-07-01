<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_parameters', function (Blueprint $table) {
            $table->increments('body_parameter_id');
            $table->string('body_parameter_name');
            $table->enum("unit",["kg","lb","%","bmi", "cm"])->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('body_parameters', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_parameters');
    }
}
