<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('valid_from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('valid_till')->default('0000-00-00 00:00:00');
            $table->integer('uses');
            $table->integer('maximum_uses');
            $table->integer('allow-promotion');
            $table->integer('show');
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
        Schema::drop('product_bundles');
    }
}
