<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbuseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abuse_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned();
            $table->string('content_type')->default('App\Hub\Node\Node');
            $table->string('reporter_ip');
            $table->integer('user_id')->unsigned();
            $table->string('report_type');
            $table->string('report_label')->nullable();
            $table->string('report_title')->nullable();
            $table->text('report_body');
            $table->text('owner_response');
            $table->boolean('is_public')->default(true);
            $table->boolean('is_disputed')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->string('pid');
            $table->tinyInteger('severity')->unsigned()->default(1);
            $table->timestamp('owner_responded_at')->nullable();
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
        Schema::drop('abuse_reports');
    }
}
