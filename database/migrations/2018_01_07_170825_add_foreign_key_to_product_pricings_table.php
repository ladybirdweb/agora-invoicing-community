<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToProductPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_pricings', function (Blueprint $table) {
            $table->integer('product_plan_id')->unsigned();
            $table->foreign('product_plan_id')->references('id')->on('product_plans')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_pricingd', function (Blueprint $table) {
            $table->dropForeign('product_pricings_product_plan_id');
        });
    }
}
