<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehicleLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('vehicle_locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('vehicle_id')->nullable();
			$table->float('lat', 10, 7);
			$table->float('lon', 10, 7);
			$table->integer('altitude');
			$table->integer('heading');
			$table->string('connection_type');
			$table->timestamps();
			$table->integer('timestamp');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('vehicle_locations');
	}

}
