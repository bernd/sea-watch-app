<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('emergency_cases', function (Blueprint $table) {
//            $table->increments('id');
//            
//            $table->string('boat_status');
//            $table->string('boat_condition');
//            $table->string('boat_type');
//            $table->string('other_involved');
//            $table->string('engine_working');
//            $table->integer('passenger_count');
//            
//            $table->text('additional_informations');
//            
//            $table->float('spotting_distance');
//            
//            $table->integer('spotting_direction');
//            
//            $table->string('picture');
//            
//            
//            
//            $table->integer('operation_area');
//            $table->string('session_token');
//            
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emergency_cases');
    }
}
