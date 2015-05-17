<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peers', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('origin_ip');
            $table->string('peer_key');
            $table->integer('protocol');
            $table->string('monitor_ip');
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
		Schema::drop('peers');
	}

}
