<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLanguagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('languages', function(Blueprint $table)
		{
			$table->foreign('user_id_edited')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('SET NULL');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('languages', function(Blueprint $table)
		{
			$table->dropForeign('languages_user_id_edited_foreign');
			$table->dropForeign('languages_user_id_foreign');
		});
	}

}
