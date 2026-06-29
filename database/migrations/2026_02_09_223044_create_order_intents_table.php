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
        Schema::create('order_intents', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->string('order_key');
            $table->unsignedBigInteger('customer_id');
            $table->double('sub_total', 10, 2)->default();
            $table->double('delivery_charge', 10, 2)->default(0);
            $table->double('discount', 10, 2)->default(0);
            $table->double('grand_total', 10, 2)->default(0);
            $table->date('order_date')->nullable();
            $table->longText('address')->nullable();
            $table->longText('landmark')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->longText('coupon_desc')->nullable();
            $table->text('order_note')->nullable();
            $table->json('order_item')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->set('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('product_order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_intents');
    }
};
