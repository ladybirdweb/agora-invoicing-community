<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->increments('id');
                        $table->integer('invoice_id')->unsigned();
                        $table->foreign('invoice_id')->references('id')->on('invoices');
                        $table->string('product_name');
                        $table->string('regular_price');
                        $table->string('quantity');
                        $table->string('discount');
                        $table->string('tax_name');
                        $table->string('tax_percentage');
                        $table->string('tax_code');
                        $table->string('discount_mode');
                        $table->string('subtotal');
                        $table->string('domain');
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
        Schema::drop('invoice_items');
    }
}
