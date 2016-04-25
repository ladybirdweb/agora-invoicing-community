<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailchimpFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailchimp_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('list_id');
            $table->string('merge_id')->unique();
            $table->string('name');
            $table->string('tag');
            $table->string('type');
            $table->string('options');
            $table->string('required');
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
        Schema::drop('mailchimp_fields');
    }
}
