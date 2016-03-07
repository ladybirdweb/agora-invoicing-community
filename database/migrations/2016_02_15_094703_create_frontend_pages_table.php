<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFrontendPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_page_id');
            $table->string('slug');
            $table->string('name');
            $table->text('content');
            $table->string('url');
            $table->integer('publish');
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
        Schema::drop('frontend_pages');
    }
}
