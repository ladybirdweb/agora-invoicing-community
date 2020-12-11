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
            $table->timestamp('valid_from');
            $table->dateTime('valid_till');
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
