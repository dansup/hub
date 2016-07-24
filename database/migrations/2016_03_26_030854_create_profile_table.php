<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->integer('owner_id')->unsigned()->nullable();
            $table->string('owner_type')->nullable();
            $table->integer('node_id')->unsigned()->nullable();
            $table->boolean('accept_peers')->default(false)->index();
            $table->boolean('public_node')->default(false)->index();
            $table->string('ownername')->nullable();
            $table->string('socialnode')->nullable();
            $table->string('twitter')->nullable();
            $table->string('github')->nullable();
            $table->string('keybase')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('bio')->nullable();
            $table->string('avatar_hash', 255)->nullable()->index();
            $table->string('avatar_path', 255)->nullable()->index();
            $table->decimal('lat', 10, 6)->nullable()->index();
            $table->decimal('lng', 10, 6)->nullable()->index();
            $table->text('nodeinfo');
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
        Schema::drop('profile');
    }
}
