<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoogle2faSecretToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google2fa_secret')->nullable();
            $table->timestamp('google2fa_activation_date')->nullable();
            $table->boolean('is_2fa_enabled')->default(0);
            $table->string('backup_code')->nullable();
            $table->integer('code_usage_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google2fa_secret');
            $table->dropColumn('google2fa_activation_date');
            $table->dropColumn('is_2fa_enabled');
            $table->dropColumn('backup_code');
            $table->dropColumn('code_usage_count');
        });
    }
}
