<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peer_requests', function(Blueprint $table) {
            $table->increments('id');
            $table->string('request_ip')->index();
            $table->string('target_ip')->index();
            $table->boolean('requesting_credentials')->default(false);
            $table->text('credentials')->nullable();
            $table->text('message')->nullable();
            $table->boolean('read')->default(false);
            $table->boolean('peered')->default(false);
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
        Schema::drop('peer_requests');
    }
}
