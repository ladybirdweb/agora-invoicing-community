<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installation_details', function (Blueprint $table) {
            $table->id();
            $table->string('installation_path')->nullable();
            $table->string('installation_ip')->nullable();
            $table->string('version')->nullable();
            $table->timestamp('last_actve')->nullable();
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
        Schema::dropIfExists('installation_details');
    }
}
