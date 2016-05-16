<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyCasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emergency_cases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('boat_status');
			$table->string('boat_condition');
			$table->string('boat_type');
			$table->string('other_involved');
			$table->string('engine_working');
			$table->integer('passenger_count');
			$table->string('women_on_board')->nullable();
			$table->string('children_on_board')->nullable();
			$table->string('disabled_on_board')->nullable();
			$table->text('additional_informations', 65535);
			$table->float('spotting_distance');
			$table->integer('spotting_direction');
			$table->string('picture');
			$table->string('session_token');
			$table->integer('operation_area');
			$table->timestamps();
			$table->string('source_type');
			$table->boolean('closed');
			$table->string('closing_reason');
		});
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
