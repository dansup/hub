<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pings', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('addr');
            $table->foreign('addr')->references('addr')->on('nodes');
            $table->integer('latency');
            $table->integer('protocol');
            $table->string('request_ip')->nullable();
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
		Schema::drop('pings');
	}

}
