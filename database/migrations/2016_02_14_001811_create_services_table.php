<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ipv6')->index();
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name', 20)->unique();
            $table->string('slug', 20)->unique();
            $table->text('description');
            $table->string('url')->unique();
            $table->integer('port')->unsigned()->nullable();
            $table->string('service_type');
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_nsfw')->default(false);
            $table->boolean('is_unlisted')->default(false);
            $table->boolean('is_inactive')->default(false);
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
