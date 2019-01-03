<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LicenseLicensePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_license_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('license_type_id');
            $table->unsignedInteger('license_permission_id');

            $table->foreign('license_type_id')->references('id')->on('license_types')->onDelete('cascade');
            $table->foreign('license_permission_id')->references('id')->on('license_permissions')->onDelete('cascade');
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
        Schema::dropIfExists('license_license_permissions');
    }
}
