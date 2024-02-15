<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_uploads', function (Blueprint $table) {
            $table->foreignId('release_type_id')->nullable()->default(null)->constrained('release_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_uploads', function (Blueprint $table) {
            $table->dropForeign(['release_type_id']);
            $table->dropColumn('release_type_id');
        });
    }
};
