<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
                        $table->integer('level');
                        $table->string('name');
                        $table->integer('active');
                        $table->string('country');
                        $table->string('state');
                        $table->string('rate');
                        $table->integer('compound');
                        $table->integer('tax_classes_id')->unsigned();
                        $table->foreign('tax_classes_id')->references('id')->on('tax_classes');
                        
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
        Schema::drop('taxes');
    }
}
