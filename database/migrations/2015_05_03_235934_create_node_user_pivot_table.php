<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeUserPivotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('node_user', function(Blueprint $table)
		{ 
			$table->string('public_key')->index();
			$table->foreign('public_key')->references('public_key')->on('nodes');
			$table->bigInteger('id')->unsigned()->index(); 
			$table->foreign('id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('node_user');
	}

}
