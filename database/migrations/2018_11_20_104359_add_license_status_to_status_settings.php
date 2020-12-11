<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLicenseStatusToStatusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_settings', function (Blueprint $table) {
            $table->integer('license_status');
            $table->integer('github_status');
            $table->integer('mailchimp_status');
            $table->integer('twitter_status');
            $table->integer('msg91_status');
            $table->integer('emailverification_status');
            $table->integer('recaptcha_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_settings');
    }
}
