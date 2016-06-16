<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
                        $table->integer('product_id')->unsigned();
                        $table->foreign('product_id')->references('id')->on('products');
                        $table->string('currency');
                        $table->integer('subscription')->unsigned();
                        $table->foreign('subscription')->references('id')->on('subscriptions');
                        $table->string('price');
                        $table->string('sales_price');
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
        Schema::drop('prices');
    }
}
