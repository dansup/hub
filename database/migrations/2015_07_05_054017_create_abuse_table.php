<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbuseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abuse', function(Blueprint $table) {
            $table->increments('id');
            $table->string('target_addr');
            $table->string('reporter_addr');
            $table->string('abuse_type');
            $table->text('abuse_message');
            $table->integer('abuse_weight')->unsigned();
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
        Schema::drop('abuse');
    }
}
