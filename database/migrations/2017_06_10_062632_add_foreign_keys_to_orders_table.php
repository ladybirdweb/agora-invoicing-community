<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('orders');
            if (! array_key_exists('orders_client_foreign', $indexesFound)) {
                $table->foreign('client')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            }
            if (! array_key_exists('orders_product_foreign', $indexesFound)) {
                $table->foreign('product')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_client_foreign');
            $table->dropForeign('orders_product_foreign');
        });
    }
}
