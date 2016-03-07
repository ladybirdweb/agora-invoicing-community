<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
                        $table->string('code');
                        $table->integer('type')->unsigned();
                        $table->foreign('type')->references('id')->on('promotion_types');
                        $table->integer('uses'); //number for how many use
                        $table->string('value');
                        $table->timestamp('start'); //start date
                        $table->timestamp('expiry'); //expiry date
                        $table->integer('lifetime'); //life time option
                        $table->integer('once'); //Apply only once per order (even if multiple items qualify)
                        $table->integer('signups'); //Apply to new signups only (must have no previous active orders)
                        $table->integer('existing_client'); //Apply to existing clients only (must have an active order to qualify)
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
        Schema::drop('promotions');
    }
}
