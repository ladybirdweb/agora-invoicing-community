<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
                        $table->integer('invoice_id')->unsigned();
                        $table->integer('number')->unique();
                        $table->integer('client')->unsigned();
                        $table->foreign('client')->references('id')->on('users');
                        $table->string('order_status');
                        $table->string('serial_key')->nullable();

                        $table->unsignedInteger('product')->nullable();
                        $table->foreign('product')->references('id')->on('products');
                        $table->string('domain');
                        $table->string('price_override');
                        $table->string('qty');
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
        Schema::drop('orders');
    }
}
