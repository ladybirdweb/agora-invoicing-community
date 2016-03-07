<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
                        $table->string('company')->default('Ladybird Web Solution');
                        $table->string('website')->default('http://www.ladybirdweb.com');
                        $table->string('phone');
                        $table->string('logo')->default('faveo.png');
                        $table->string('address');
                        $table->string('host');
                        $table->integer('port');
                        $table->string('encryption');
                        $table->string('email');
                        $table->string('password');
                        $table->integer('error_log');
                        $table->string('error_email');
                        $table->integer('cart');
                        $table->integer('subscription_over');
                        $table->integer('subscription_going_to_end');
                        $table->integer('forgot_password');
                        $table->integer('order_mail');
                        $table->integer('welcome_mail');
                        $table->integer('invoice_template');
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
        Schema::drop('settings');
    }
}
