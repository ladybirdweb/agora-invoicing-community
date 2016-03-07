<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
                        $table->string('name');
                        $table->string('description');
                        $table->string('category');
                        $table->integer('parent');
                        $table->integer('type')->unsigned();
                        $table->foreign('type')->references('id')->on('product_types');
                        $table->integer('group')->unsigned();
                        $table->foreign('group')->references('id')->on('product_groups');
                        $table->string('welcome_email');
                        $table->integer('require_domain');
                        $table->integer('stock_control'); //1 or 0
                        $table->integer('stock_qty'); //number of stocks
                        $table->integer('sort_order');
                        $table->integer('tax_apply'); //1 or 0
                        $table->integer('retired'); //1 or 0 hidden from admin drop down
                        $table->integer('hidden'); //1 or 0 hide from order form
                        $table->integer('multiple_qty'); //1 or 0
                        $table->string('auto_terminate');
                        $table->integer('setup_order_placed');
                        $table->integer('setup_first_payment');
                        $table->integer('setup_accept_manually');
                        $table->integer('no_auto_setup');
                        $table->string('shoping_cart_link');
                        $table->string('file');
                        $table->string('image');
                        $table->string('process_url');
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
        Schema::drop('products');
    }
}
