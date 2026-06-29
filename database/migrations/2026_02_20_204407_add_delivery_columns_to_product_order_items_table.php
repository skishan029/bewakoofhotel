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
        Schema::table('product_order_items', function (Blueprint $table) {
            $table->double('delivery_charge', 10, 2)->default(0)->after('price');
            $table->double('total_delivery_charge', 10, 2)->default(0)->after('delivery_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_order_items', function (Blueprint $table) {
            $table->dropColumn('delivery_charge');
            $table->dropColumn('total_delivery_charge');
        });
    }
};
