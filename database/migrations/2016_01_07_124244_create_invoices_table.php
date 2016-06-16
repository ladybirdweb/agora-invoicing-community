<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
                        $table->integer('user_id')->unsigned();
                        $table->foreign('user_id')->references('id')->on('users');
                        $table->string('number');
                        $table->timestamp('date');
                        $table->string('discount');
                        $table->string('discount_mode');
                        $table->string('coupon_code');
                        $table->string('grand_price');
                        $table->string('status');
                        $table->string('currency')->default('USD');
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
        Schema::drop('invoices');
    }
}
