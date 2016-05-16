<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyCaseLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emergency_case_locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('emergency_case_id');
			$table->float('lat', 10, 7);
			$table->float('lon', 10, 7);
			$table->integer('accuracy');
			$table->integer('heading');
			$table->timestamps();
			$table->string('connection_type', 60)->nullable();
			$table->text('message', 65535)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('emergency_case_locations');
	}

}
