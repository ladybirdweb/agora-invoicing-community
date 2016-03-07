<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigurableOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('configurable_options', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('group_id')->unsigned();
                        $table->foreign('group_id')->references('id')->on('product_groups');
                        $table->integer('type');
                        $table->string('title');
                        $table->string('options');
                        $table->integer('price');
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
		Schema::drop('configurable_options');
	}

}
