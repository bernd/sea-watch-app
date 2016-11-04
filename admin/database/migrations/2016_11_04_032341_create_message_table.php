<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('messages', function(Blueprint $table)
		{
            //['message_type', 'author_id', 'text', 'seen_by', 'received_by'];
            
			$table->increments('id');
			$table->timestamps();
			$table->string('message_type');
			$table->integer('author_id');
			$table->text('text');
			$table->text('seen_by')->nullable();
			$table->text('received_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
}
