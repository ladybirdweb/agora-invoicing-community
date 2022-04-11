<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateStatusToStatusSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('status_settings', 'update_settings')) {
            Schema::table('status_settings', function (Blueprint $table) {
                $table->boolean('update_settings')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('status_settings', function (Blueprint $table) {
            $table->dropColumn('update_settings');
        });
    }
}
