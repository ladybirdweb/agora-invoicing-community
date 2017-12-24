<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                Schema::table('taxes', function (Blueprint $table) {
                    $table->integer('product_name_id')->unsigned();
            $table->foreign('product_name_id')->references('id')->on('product_names')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('taxes', function (Blueprint $table) {
            $table->dropForeign('taxes_product_name_id');
        });    }
}
