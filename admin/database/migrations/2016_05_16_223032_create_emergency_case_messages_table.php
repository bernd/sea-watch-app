<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEmergencyCaseMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('emergency_case_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('emergency_case_id');
			$table->integer('emergency_case_location_id');
			$table->string('receiver_type');
			$table->string('receiver_id');
			$table->string('sender_type');
			$table->string('sender_id');
			$table->timestamps();
			$table->integer('seen');
			$table->text('message', 16777215)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('emergency_case_messages');
	}

}
