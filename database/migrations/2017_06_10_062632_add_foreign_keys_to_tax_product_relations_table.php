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
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('tax_product_relations');
            if (! array_key_exists('tax_product_relations_product_id_foreign', $indexesFound)) {
                $table->foreign('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            }
            if (! array_key_exists('tax_product_relations_tax_class_id_foreign', $indexesFound)) {
                $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        Schema::table('tax_product_relations', function (Blueprint $table) {
            $table->dropForeign('tax_product_relations_product_id_foreign');
            $table->dropForeign('tax_product_relations_tax_id_foreign');
        });
    }
}
