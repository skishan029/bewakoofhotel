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
        Schema::create('product_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->set('plate_type', ['1','2'])->default('1')->comment('1=Full, 2=Half');
            $table->integer('quantity')->nullable();
            $table->double('price', 20,2)->nullable();
            $table->double('total', 20,2)->nullable();
            $table->string('product_name')->nullable();
            $table->longText('product_details')->nullable();
            $table->set('full_lbl_show', ['1','2'])->default('1')->comment('1=Show 2=None');
            $table->set('status', ['1','2'])->nullable()->comment('1=pending order 2=place order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_order_items');
    }
};
