<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmergencyCaseMessagesasdasd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('operation_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->json('polygon_coordinates');
            $table->integer('user_id');
            $table->integer('active');
            $table->timestamps();
        });
        Schema::create('emergency_cases', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('boat_status');
            $table->string('boat_condition');
            $table->string('boat_type');
            $table->string('other_involved');
            $table->string('engine_working');
            $table->integer('passenger_count');
            
            $table->text('additional_informations');
            
            $table->float('spotting_distance');
            
            $table->integer('spotting_direction');
            
            $table->string('picture');
            
            $table->string('session_token');
            
            $table->integer('operation_area');
            
            $table->string('source_type');
            
            $table->timestamps();
        });
        
        Schema::create('emergency_case_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emergency_case_id');
            $table->integer('emergency_case_location_id');
            $table->string('receiver_type');
            $table->string('receiver_id');
            $table->string('sender_type');
            $table->string('sender_id');
            $table->string('message');
            $table->integer('seen');
            $table->timestamps();
            $table->foreign('emergency_case_id')->references('id')->on('emergency_cases');
            $table->foreign('emergency_case_location_id')->references('id')->on('emergency_case_locations');
        });
        
        Schema::create('emergency_case_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emergency_case_id');
            $table->float('lat', 10, 7);
            $table->float('lon', 10, 7);
            $table->integer('accuracy');
            $table->integer('heading');
            $table->string('connection_type'); //internet or sms
            $table->timestamps();
            $table->foreign('emergency_case_id')->references('id')->on('emergency_cases');
        });
        Schema::create('emergency_case_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emergency_case_id');
            $table->integer('emergency_case_location_id');
            $table->string('receiver_type');
            $table->string('receiver_id');
            $table->string('sender_type');
            $table->string('sender_id');
            $table->timestamps();
            $table->foreign('emergency_case_id')->references('id')->on('emergency_cases');
            $table->foreign('emergency_case_location_id')->references('id')->on('emergency_case_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_cases', function (Blueprint $table) {
            //
        });
    }
}
