<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned();
            $table->string('owner_type');
            $table->boolean('public_profile')->default(true);
            $table->boolean('is_discoverable')->default(true);
            $table->boolean('public_location')->default(true);
            $table->boolean('internet_listed')->default(false);
            $table->boolean('global_map')->default(false);
            $table->boolean('network_map')->default(true);
            $table->boolean('traffic_map')->default(true);
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
        Schema::drop('privacy');
    }
}
