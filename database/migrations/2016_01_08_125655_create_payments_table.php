<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('parent_id')->unsigned();
                        $table->integer('invoice_id')->unsigned();
                        $table->integer('user_id')->unsigned();
                        $table->string('amount');
                        $table->string('payment_method');
                        $table->string('payment_status');
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
		Schema::drop('payments');
	}

}
