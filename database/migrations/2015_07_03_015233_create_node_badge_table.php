<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeBadgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_badge', function(Blueprint $table) {
            $table->increments('id');
            $table->string('public_key');
            $table->foreign('public_key')->references('public_key')->on('nodes');
            $table->string('type')->default('activity')->index();
            $table->string('label');
            $table->string('url')->nullable();
            $table->integer('weight')->default(0.5)->index();
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
        Schema::drop('node_badge');
    }
}
