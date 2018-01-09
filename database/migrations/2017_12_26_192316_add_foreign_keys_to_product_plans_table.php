<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_plans', function (Blueprint $table) {
            $table->integer('product_name_id')->unsigned();
            $table->foreign('product_name_id')->references('id')->on('product_names')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('product_names')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('product_names')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_plans', function (Blueprint $table) {
            $table->dropForeign('product_plans_product_name_id');
            $table->dropForeign('product_plans_category_id');
            $table->dropForeign('product_plans_type_id');
        });
    }
}
