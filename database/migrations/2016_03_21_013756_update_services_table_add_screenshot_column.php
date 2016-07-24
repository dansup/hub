<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateServicesTableAddScreenshotColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function ($table) {
            $table->boolean('has_screenshot')->default(false);
            $table->string('screenshot_id')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamp('screenshot_updated_at')->nullable();
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
