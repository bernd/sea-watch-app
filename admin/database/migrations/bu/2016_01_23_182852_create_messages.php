<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_case_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('emergency_case_id');
            $table->integer('emergency_case_location_id');
            $table->string('receiver_type');
            $table->string('receiver_id');
            $table->string('sender_type');
            $table->string('sender_id');
            $table->string('message');
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
        //
    }
}
