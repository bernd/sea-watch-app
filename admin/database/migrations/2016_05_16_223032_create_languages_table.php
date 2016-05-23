<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('languages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('position')->nullable();
			$table->string('name', 50)->unique();
			$table->string('lang_code', 10)->unique();
			$table->integer('user_id')->unsigned()->nullable()->index('languages_user_id_foreign');
			$table->integer('user_id_edited')->unsigned()->nullable()->index('languages_user_id_edited_foreign');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('languages');
	}

}
