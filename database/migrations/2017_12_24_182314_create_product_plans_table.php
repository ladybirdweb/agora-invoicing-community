<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_plans', function (Blueprint $table) {
            $table->increments('id');
             $table->string('name')->unique('name');
             $table->string('product_group');
            $table->string('description', 2000);
             $table->string('shoping_cart_link');
             $table->string('github_owner', 225);
              $table->string('github_repository_name', 225);
              $table->string('image');
              $table->string('file');
               $table->boolean('status');
                $table->boolean('require_domain');
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
        Schema::dropIfExists('product_plans');
    }
}
