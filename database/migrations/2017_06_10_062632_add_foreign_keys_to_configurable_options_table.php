<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToConfigurableOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurable_options', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('product_groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configurable_options', function (Blueprint $table) {
            $table->dropForeign('configurable_options_group_id_foreign');
        });
    }
}
