<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaxProductRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_product_relations', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('tax_class_id', 'tax_product_relations_tax_id_foreign')->references('id')->on('tax_classes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_product_relations', function (Blueprint $table) {
            $table->dropForeign('tax_product_relations_product_id_foreign');
            $table->dropForeign('tax_product_relations_tax_id_foreign');
        });
    }
}
