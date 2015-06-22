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
	            $table->string('public_key')->primary();
	            $table->string('addr')->index();
	            $table->string('hostname')->nullable()->unique();
	            $table->string('ownername')->nullable();
	            $table->string('city')->nullable();
	            $table->string('province')->nullable();
	            $table->string('country')->nullable();
	            $table->integer('version')->unsigned()->index();
	            $table->integer('latency')->unsigned()->default(0);
	            $table->text('bio')->nullable();
	            $table->integer('privacy_level')->unsigned()->default(1);
	            $table->string('avatar_hash', 255)->nullable();
	            $table->decimal('lat', 10, 6)->nullable()->index();
	            $table->decimal('lng', 10, 6)->nullable()->index();
	            $table->softDeletes();
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
