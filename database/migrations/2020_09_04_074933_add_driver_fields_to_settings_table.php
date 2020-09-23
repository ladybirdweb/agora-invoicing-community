<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverFieldsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('key')->nullable();
            $table->string('secret')->nullable();
            $table->string('region')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('sending_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('secret');
            $table->dropColumn('region');
            $table->dropColumn('domain');
            $table->dropColumn('sending_status');
        });
    }
}
