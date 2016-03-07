<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            \DB::unprepared(File::get(public_path('dist/sql/countries/countries.sql')));
            \DB::unprepared(File::get(public_path('dist/sql/countries/states.sql')));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
                Schema::drop('states');
		Schema::drop('countries');
	}

}
