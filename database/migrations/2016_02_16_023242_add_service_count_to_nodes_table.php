<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceCountToNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nodes', function ($table) {
            $table->integer('serviceCount')->unsigned()->default(0);
            $table->integer('abuseCount')->unsigned()->default(0);
            $table->integer('subscriberCount')->unsigned()->default(0);
            $table->integer('followingCount')->unsigned()->default(0);
            $table->decimal('wotScore', 5,3)->unsigned()->default(0.5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
