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
        Schema::table('auto_renewals', function (Blueprint $table) {
            if (!Schema::hasColumn('auto_renewals', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable();
                $table->foreign('order_id')->references('id')->on('orders');
            }
            if (!Schema::hasColumn('auto_renewals', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
        });
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
