<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('order_id', 'payment_method')) {
        Schema::table('auto_renewals', function (Blueprint $table) {
            $table->string('order_id')->nullable();
            $table->string('payment_method')->nullable();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_renewals', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'payment_method']);
        });
    }
};
