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
        Schema::table('product_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->string('landmark')->nullable()->after('address');
            $table->decimal('delivery_charge', 10, 2)->default(0)->after('sub_total');
            $table->enum('order_status', [
                'pending',
                'accepted',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->nullable()->after('status');
            $table->boolean('is_paid')->default(0)->after('order_status');
            $table->text('order_note')->nullable()->after('is_paid');
            $table->string('transaction_id')->nullable()->after('transaction_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_id',
                'landmark',
                'delivery_charge',
                'is_paid',
                'order_status',
                'transaction_id',
                'order_note',
            ]);
        });
    }
};
