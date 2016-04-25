<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('company');
            $table->string('mobile');
            $table->string('address');
            $table->string('town');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->string('profile_pic');
            $table->integer('active');
            $table->integer('timezone_id')->unsigned()->default('114');
            $table->foreign('timezone_id')->references('timezone')->on('id');
            $table->string('role');
            $table->decimal('debit', 8, 2);

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
