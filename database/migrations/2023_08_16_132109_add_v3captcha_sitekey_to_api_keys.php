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
        if (! Schema::hasColumn('v3captcha_sitekey', 'v3captcha_secretCheck')) {
        Schema::table('api_keys', function (Blueprint $table) {
             $table->string('v3captcha_sitekey', 255)->nullable();
              $table->string('v3captcha_secretCheck', 255)->nullable();
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
        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn('v3captcha_sitekey');
            $table->dropColumn('v3captcha_secretCheck');
        });
    }
};
