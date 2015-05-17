<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('services', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('addr')->index();
            $table->text('bio');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
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
		Schema::drop('services');
	}

}
