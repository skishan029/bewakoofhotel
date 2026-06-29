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
        Schema::table('order_intents', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable()->after('product_order_id');
            $table->unsignedBigInteger('sub_region_id')->nullable()->after('region_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_intents', function (Blueprint $table) {
            $table->dropColumn(['region_id', 'sub_region_id']);
        });
    }
};
