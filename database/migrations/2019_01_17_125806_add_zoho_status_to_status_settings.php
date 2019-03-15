<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZohoStatusToStatusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_settings', function (Blueprint $table) {
            $table->boolean('zoho_status')->nullable();
            $table->boolean('rzp_status')->nullable();
            $table->boolean('mailchimp_product_status')->nullable();
            $table->boolean('mailchimp_ispaid_status')->nullable();
            $table->boolean('terms')->nullable();
            $table->boolean('pipedrive_status')->nullable();
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
