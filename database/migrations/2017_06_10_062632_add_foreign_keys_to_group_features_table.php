<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGroupFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_features', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('group_features');
            if (! array_key_exists('group_features_group_id_foreign', $indexesFound)) {
                $table->foreign('group_id')->references('id')->on('product_groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        Schema::table('group_features', function (Blueprint $table) {
            $table->dropForeign('group_features_group_id_foreign');
        });
    }
}
