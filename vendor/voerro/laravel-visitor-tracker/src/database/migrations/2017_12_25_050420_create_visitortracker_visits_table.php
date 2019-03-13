<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorTrackerVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitortracker_visits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('ip', 40);
            $table->string('method')->nullable();
            $table->boolean('is_ajax')->default(false);
            $table->text('url')->nullable();
            $table->text('referer')->nullable();

            $table->string('user_agent')->nullable();
            $table->boolean('is_desktop')->default(false);
            $table->boolean('is_mobile')->default(false);
            $table->boolean('is_bot')->default(false);
            $table->string('bot')->nullable();
            $table->string('os_family')->default('');
            $table->string('os')->default('');
            $table->string('browser_family')->default('');
            $table->string('browser')->default('');

            $table->boolean('is_login_attempt')->default(false);

            $table->string('country')->default('');
            $table->string('country_code')->default('');
            $table->string('city')->default('');
            $table->double('lat')->nullable();
            $table->double('long')->nullable();

            $table->string('browser_language_family', 4)->default('');
            $table->string('browser_language', 7)->default('');
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
        Schema::dropIfExists('visitortracker_visits');
    }
}
