<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	 	Schema::create('nodes', function(Blueprint $table) {
	            $table->increments('id');
	            $table->string('addr')->unique();
	            $table->string('public_key')->index();
	            $table->string('last_seen');
	            $table->string('first_seen');
	            $table->string('hostname')->index();
	            $table->string('ownername');
	            $table->string('city');
	            $table->string('province');
	            $table->string('country');
	            $table->integer('version')->index();
	            $table->integer('latency');
	            $table->decimal('lat', 10, 6)->index();
	            $table->decimal('lng', 10, 6)->index();
	        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nodes');
	}

}
