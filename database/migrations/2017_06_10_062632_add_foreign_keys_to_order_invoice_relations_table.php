<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrderInvoiceRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_invoice_relations', function (Blueprint $table) {
            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_invoice_relations', function (Blueprint $table) {
            $table->dropForeign('order_invoice_relations_invoice_id_foreign');
            $table->dropForeign('order_invoice_relations_order_id_foreign');
        });
    }
}
