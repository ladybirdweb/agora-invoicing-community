<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->boolean('license_status')->nullable();
             $table->boolean('github_status')->nullable();
              $table->boolean('mailchimp_status')->nullable();
              $table->boolean('twitter_status')->nullable();
              $table->boolean('msg91_status')->nullable();
               $table->boolean('emailverification_status')->nullable();
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
