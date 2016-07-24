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
	            $table->string('public_key')->index();
	            $table->string('addr')->index();
	            $table->string('hostname')->nullable()->unique();
	            $table->integer('version')->unsigned()->nullable()->index();
	            $table->integer('latency')->unsigned()->default(0);
	            $table->decimal('lat', 10, 6)->nullable()->index();
	            $table->decimal('lng', 10, 6)->nullable()->index();
	            $table->text('nodeinfo');
	            $table->timestamp('nodeinfo_updated_at');
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
