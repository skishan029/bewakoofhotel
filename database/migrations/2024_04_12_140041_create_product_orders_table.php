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
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->string('order_key')->unique();
            $table->date('order_date');
            $table->double('sub_total', 20,2)->nullable();
            $table->double('discount', 20,2)->nullable();
            $table->double('grand_total', 20,2)->nullable();
            $table->string('name')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('alt_number')->nullable();
            $table->string('address')->nullable();
            $table->string('payment_option')->nullable();
            $table->string('transaction_no')->nullable();
            $table->string('delivery_mode')->nullable();
            $table->double('given_amount', 20,2)->nullable();
            $table->double('return_amount', 20,2)->nullable();
            $table->set('status', ['1','2'])->nullable()->comment('1=pending order 2=place order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_orders');
    }
};
