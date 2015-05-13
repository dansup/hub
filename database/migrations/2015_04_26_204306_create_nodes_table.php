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
	            $table->string('addr')->primary();
	            $table->string('public_key')->index();
	            $table->string('hostname')->index();
	            $table->string('ownername');
	            $table->string('city');
	            $table->string('province');
	            $table->string('country');
	            $table->integer('version')->index();
	            $table->integer('latency');
	            $table->text('bio');
	            $table->integer('privacy_level')->unsigned()->default(1);
	            $table->decimal('lat', 10, 6)->index();
	            $table->decimal('lng', 10, 6)->index();
	            $table->timestamps();
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
