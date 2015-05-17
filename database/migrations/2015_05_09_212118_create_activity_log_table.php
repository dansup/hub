<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log', function(Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('actor_user_id')->index();
            $table->integer('action_user_id')->nullable();
            $table->string('action_id')->index();
            $table->string('actor_type', 72)->nullable();
            $table->string('action_type', 72)->nullable();
            $table->string('action', 32)->nullable();
            $table->string('description')->nullable();
            $table->text('details')->nullable();
            $table->boolean('developer');
            $table->string('ip_address', 64);
            $table->string('user_agent');
            $table->string('source', 64);
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
        Schema::drop('activity_log');
    }

}