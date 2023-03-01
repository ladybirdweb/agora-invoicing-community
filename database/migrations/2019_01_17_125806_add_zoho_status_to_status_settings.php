<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('status_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('status_settings', 'zoho_status')) {
                $table->boolean('zoho_status')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'rzp_status')) {
                $table->boolean('rzp_status')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'mailchimp_product_status')) {
                $table->boolean('mailchimp_product_status')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'mailchimp_ispaid_status')) {
                $table->boolean('mailchimp_ispaid_status')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'terms')) {
                $table->boolean('terms')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'pipedrive_status')) {
                $table->boolean('pipedrive_status')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'subs_expirymail')) {
                $table->boolean('subs_expirymail')->nullable();
            }
            if (! Schema::hasColumn('status_settings', 'post_expirymail')) {
                $table->boolean('post_expirymail')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_settings', function (Blueprint $table) {
            $table->dropColumn([
                'zoho_status',
                'rzp_status',
                'mailchimp_product_status',
                'mailchimp_ispaid_status',
                'terms',
                'pipedrive_status',
                'post_expirymail',
                'subs_expirymail',
            ]);
        });
    }
};
